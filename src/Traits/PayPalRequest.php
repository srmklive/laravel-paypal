<?php

namespace Srmklive\PayPal\Traits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException as HttpBadResponseException;
use GuzzleHttp\Exception\ClientException as HttpClientException;
use GuzzleHttp\Exception\ServerException as HttpServerException;
use Illuminate\Support\Collection;

trait PayPalRequest
{
    /**
     * Http Client class object.
     *
     * @var HttpClient
     */
    private $client;

    /**
     * PayPal API mode to be used.
     *
     * @var string
     */
    public $mode;

    /**
     * Request data to be sent to PayPal.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $post;

    /**
     * PayPal API configuration.
     *
     * @var array
     */
    private $config;

    /**
     * Default currency for PayPal.
     *
     * @var string
     */
    private $currency;

    /**
     * Additional options for PayPal API request.
     *
     * @var array
     */
    private $options;

    /**
     * Default payment action for PayPal.
     *
     * @var string
     */
    private $paymentAction;

    /**
     * Default locale for PayPal.
     *
     * @var string
     */
    private $locale;

    /**
     * PayPal API Endpoint.
     *
     * @var string
     */
    private $apiUrl;

    /**
     * IPN notification url for PayPal.
     *
     * @var string
     */
    private $notifyUrl;

    /**
     * Http Client request body parameter name.
     *
     * @var string
     */
    private $httpBodyParam;

    /**
     * Function To Set PayPal API Configuration.
     *
     * @param array $config
     *
     * @return void
     */
    private function setConfig(array $config = [])
    {
        // Setting Http Client
        $this->client = $this->setClient();

        // Set Api Credentials
        if (function_exists('config')) {
            $this->setApiCredentials(
                config('paypal')
            );
        } elseif (!empty($config)) {
            $this->setApiCredentials($config);
        }

        $this->setRequestData();
    }

    /**
     * Function to initialize Http Client.
     *
     * @return HttpClient
     */
    protected function setClient()
    {
        return new HttpClient([
            'curl' => [
                CURLOPT_SSLVERSION     => CURL_SSLVERSION_TLSv1_2,
                CURLOPT_SSL_VERIFYPEER => false,
            ],
        ]);
    }

    /**
     * Set PayPal API Credentials.
     *
     * @param array $credentials
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setApiCredentials($credentials)
    {
        // Setting Default PayPal Mode If not set
        $this->setApiEnvironment($credentials);

        // Set API configuration for the PayPal provider
        $this->setApiProviderConfiguration($credentials);

        // Set default currency.
        $this->setCurrency($credentials['currency']);

        // Set default payment action.
        $this->paymentAction = !empty($this->config['payment_action']) ? $this->config['payment_action'] : 'Sale';

        // Set default locale.
        $this->locale = !empty($this->config['locale']) ? $this->config['locale'] : 'en_US';

        // Set PayPal API Endpoint.
        $this->apiUrl = $this->config['api_url'];

        // Set PayPal IPN Notification URL
        $this->notifyUrl = $credentials['notify_url'];
    }

    /**
     * Set API environment to be used by PayPal.
     *
     * @param array $credentials
     *
     * @return void
     */
    private function setApiEnvironment($credentials)
    {
        if (empty($credentials['mode']) || !in_array($credentials['mode'], ['sandbox', 'live'])) {
            $this->mode = 'live';
        } else {
            $this->mode = $credentials['mode'];
        }
    }

    /**
     * Set configuration details for the provider.
     *
     * @param array $credentials
     *
     * @throws \Exception
     *
     * @return void
     */
    private function setApiProviderConfiguration($credentials)
    {
        // Setting PayPal API Credentials
        collect($credentials[$this->mode])->map(function ($value, $key) {
            $this->config[$key] = $value;
        });

        // Setup PayPal API Signature value to use.
        $this->config['signature'] = empty($this->config['certificate']) ?
            $this->config['secret'] : file_get_contents($this->config['certificate']);

        if ($this instanceof \Srmklive\PayPal\Services\AdaptivePayments) {
            $this->setAdaptivePaymentsOptions();
        } elseif ($this instanceof \Srmklive\PayPal\Services\ExpressCheckout) {
            $this->setExpressCheckoutOptions($credentials);
        } else {
            throw new \Exception('Invalid api credentials provided for PayPal!. Please provide the right api credentials.');
        }
    }

    /**
     * Setup request data to be sent to PayPal.
     *
     * @param array $data
     *
     * @return \Illuminate\Support\Collection
     */
    protected function setRequestData(array $data = [])
    {
        if (($this->post instanceof Collection) && (!$this->post->isEmpty())) {
            unset($this->post);
        }

        $this->post = new Collection($data);

        return $this->post;
    }

    /**
     * Set other/override PayPal API parameters.
     *
     * @param array $options
     *
     * @return $this
     */
    public function addOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Function to set currency.
     *
     * @param string $currency
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setCurrency($currency = 'USD')
    {
        $allowedCurrencies = ['AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD', 'RUB'];

        // Check if provided currency is valid.
        if (!in_array($currency, $allowedCurrencies)) {
            throw new \Exception('Currency is not supported by PayPal.');
        }

        $this->currency = $currency;

        return $this;
    }

    /**
     * Retrieve PayPal IPN Response.
     *
     * @param array $post
     *
     * @return array
     */
    public function verifyIPN($post)
    {
        $this->setRequestData($post);

        $this->apiUrl = $this->config['gateway_url'].'/cgi-bin/webscr';

        return $this->doPayPalRequest('verifyipn');
    }

    /**
     * Refund PayPal Transaction.
     *
     * @param string $transaction
     * @param float  $amount
     *
     * @return array
     */
    public function refundTransaction($transaction, $amount = 0.00)
    {
        $this->setRequestData([
            'TRANSACTIONID' => $transaction,
        ]);

        if ($amount > 0) {
            $this->post = $this->post->merge([
                'REFUNDTYPE' => 'Partial',
                'AMT'        => $amount,
            ]);
        }

        return $this->doPayPalRequest('RefundTransaction');
    }

    /**
     * Search Transactions On PayPal.
     *
     * @param array $post
     *
     * @return array
     */
    public function searchTransactions($post)
    {
        $this->setRequestData($post);

        return $this->doPayPalRequest('TransactionSearch');
    }

    /**
     * Create request payload to be sent to PayPal.
     *
     * @param string $method
     */
    private function createRequestPayload($method)
    {
        $config = array_merge([
            'USER'      => $this->config['username'],
            'PWD'       => $this->config['password'],
            'SIGNATURE' => $this->config['signature'],
            'VERSION'   => 123,
            'METHOD'    => $method,
        ], $this->options);

        $this->post = $this->post->merge($config);
        if ($method === 'verifyipn') {
            $this->post->forget('METHOD');
        }
    }

    /**
     * Perform PayPal API request & return response.
     *
     * @throws \Exception
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    private function makeHttpRequest()
    {
        try {
            return $this->client->post($this->apiUrl, [
                $this->httpBodyParam => $this->post->toArray(),
            ])->getBody();
        } catch (HttpClientException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (HttpServerException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (HttpBadResponseException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        }
    }

    /**
     * Function To Perform PayPal API Request.
     *
     * @param string $method
     *
     * @throws \Exception
     *
     * @return array|\Psr\Http\Message\StreamInterface
     */
    private function doPayPalRequest($method)
    {
        // Setup PayPal API Request Payload
        $this->createRequestPayload($method);

        try {
            // Perform PayPal HTTP API request.
            $response = $this->makeHttpRequest();

            return $this->retrieveData($method, $response);
        } catch (\Exception $e) {
            $message = collect($e->getTrace())->implode('\n');
        }

        return [
            'type'      => 'error',
            'message'   => $message,
        ];
    }

    /**
     * Parse PayPal NVP Response.
     *
     * @param string                                  $method
     * @param array|\Psr\Http\Message\StreamInterface $response
     *
     * @return array
     */
    private function retrieveData($method, $response)
    {
        if ($method === 'verifyipn') {
            return $response;
        }

        parse_str($response, $output);

        return $output;
    }
}
