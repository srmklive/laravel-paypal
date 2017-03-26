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

        // Set options to be empty.
        $this->options = [];

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
                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            ],
        ]);
    }

    /**
     * Set PayPal API Credentials.
     *
     * @param array  $credentials
     * @param string $mode
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setApiCredentials($credentials, $mode = '')
    {
        // Setting Default PayPal Mode If not set
        if (empty($credentials['mode']) ||
            (!in_array($credentials['mode'], ['sandbox', 'live']))
        ) {
            $credentials['mode'] = 'live';
        }

        // Setting default mode.
        if (empty($mode)) {
            $mode = $credentials['mode'];
        }

        // Setting PayPal API Credentials
        foreach ($credentials[$mode] as $key => $value) {
            $this->config[$key] = $value;
        }

        // Setup PayPal API Signature value to use.
        if (!empty($this->config['secret'])) {
            $this->config['signature'] = $this->config['secret'];
        } else {
            $this->config['signature'] = file_get_contents($this->config['certificate']);
        }

        if ($this instanceof \Srmklive\PayPal\Services\AdaptivePayments) {
            $this->setAdaptivePaymentsOptions($mode);
        } elseif ($this instanceof \Srmklive\PayPal\Services\ExpressCheckout) {
            $this->setExpressCheckoutOptions($credentials, $mode);
        } else {
            throw new \Exception('Invalid api credentials provided for PayPal!. Please provide the right api credentials.');
        }

        // Set default currency.
        $this->setCurrency($credentials['currency']);

        // Set default payment action.
        $this->paymentAction = !empty($this->config['payment_action']) ?
            $this->config['payment_action'] : 'Sale';

        // Set default locale.
        $this->locale = !empty($this->config['locale']) ?
            $this->config['locale'] : 'en_US';

        // Set PayPal IPN Notification URL
        $this->notifyUrl = $credentials['notify_url'];
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
        if (($this->post instanceof Collection) && ($this->post->isNotEmpty())) {
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

        if ($amount) {
            $this->post->merge([
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
        // Setting API Credentials, Version & Method
        $this->post->merge([
            'USER'      => $this->config['username'],
            'PWD'       => $this->config['password'],
            'SIGNATURE' => $this->config['signature'],
            'VERSION'   => 123,
            'METHOD'    => $method,
        ]);

        // Set PayPal API Request Endpoint.
        $post_url = $this->config['api_url'];

        // Checking Whether The Request Is PayPal IPN Response
        if ($method == 'verifyipn') {
            $this->post = $this->post->filter(function ($value, $key) {
                return ($key !== 'METHOD') ? $value : '';
            });

            $post_url = $this->config['gateway_url'].'/cgi-bin/webscr';
        }

        // Merge $options array if set.
        $this->post->merge($this->options);

        try {
            $request = $this->client->post($post_url, [
                $this->httpBodyParam => $this->post->toArray(),
            ]);

            $response = $request->getBody();

            return ($method == 'verifyipn') ? $response : $this->retrieveData($response);
        } catch (HttpClientException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (HttpServerException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (HttpBadResponseException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return [
            'type'      => 'error',
            'message'   => $message,
        ];
    }

    /**
     * Parse PayPal NVP Response.
     *
     * @param string $request
     * @param array  $response
     *
     * @return array
     */
    private function retrieveData($request, array $response = null)
    {
        parse_str($request, $response);

        return $response;
    }
}
