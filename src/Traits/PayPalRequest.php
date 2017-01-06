<?php

namespace Srmklive\PayPal\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

trait PayPalRequest
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var array
     */
    private $options;

    /**
     * Function To Set PayPal API Configuration.
     *
     * @return void
     */
    private function setConfig()
    {
        // Setting Http Client
        $this->client = $this->setClient();

        // Set Api Credentials
        if (function_exists('config')) {
            $this->setApiCredentials(
                config('paypal')
            );
        }
    }

    /**
     * Function to Guzzle Client class object.
     *
     * @return Client
     */
    protected function setClient()
    {
        return new Client([
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
        } else {
            $this->setExpressCheckoutOptions($credentials, $mode);
        }

        // Set default currency.
        $this->setCurrency($credentials['currency']);
    }

    /**
     * Set other/override PayPal API parameters.
     *
     * @param array $options
     */
    public function addOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Set ExpressCheckout API endpoints & options.
     *
     * @param array  $credentials
     * @param string $mode
     *
     * @return void
     */
    private function setExpressCheckoutOptions($credentials, $mode)
    {
        // Setting API Endpoints
        if ($mode == 'sandbox') {
            $this->config['api_url'] = !empty($this->config['secret']) ?
                'https://api-3t.sandbox.paypal.com/nvp' : 'https://api.sandbox.paypal.com/nvp';

            $this->config['gateway_url'] = 'https://www.sandbox.paypal.com';
        } else {
            $this->config['api_url'] = !empty($this->config['secret']) ?
                'https://api-3t.paypal.com/nvp' : 'https://api.paypal.com/nvp';

            $this->config['gateway_url'] = 'https://www.paypal.com';
        }

        // Adding params outside sandbox / live array
        $this->config['payment_action'] = $credentials['payment_action'];
        $this->config['notify_url'] = $credentials['notify_url'];
        $this->config['locale'] = $credentials['locale'];
    }

    /**
     * Set AdaptivePayments API endpoints & options.
     *
     * @param string $mode
     *
     * @return void
     */
    private function setAdaptivePaymentsOptions($mode)
    {
        if ($mode == 'sandbox') {
            $this->config['api_url'] = 'https://svcs.sandbox.paypal.com/AdaptivePayments';
            $this->config['gateway_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $this->config['api_url'] = 'https://svcs.paypal.com/AdaptivePayments';
            $this->config['gateway_url'] = 'https://www.paypal.com/cgi-bin/webscr';
        }
    }

    /**
     * Function to set currency.
     *
     * @param string $currency
     *
     * @throws \Exception
     *
     * @return string
     */
    public function setCurrency($currency = 'USD')
    {
        $allowedCurrencies = ['AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD', 'RUB'];

        // Check if provided currency is valid.
        if (!in_array($currency, $allowedCurrencies)) {
            throw new \Exception('Currency is not supported by PayPal.');
        }

        $this->currency = $currency;
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
        $response = $this->doPayPalRequest('verifyipn', $post);

        return $response;
    }

    /**
     * Refund PayPal Transaction.
     *
     * @param string $transaction
     *
     * @return array
     */
    public function refundTransaction($transaction)
    {
        $post = [
            'TRANSACTIONID' => $transaction,
        ];

        $response = $this->doPayPalRequest('RefundTransaction', $post);

        return $response;
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
        $response = $this->doPayPalRequest('TransactionSearch', $post);

        return $response;
    }

    /**
     * Function To Perform PayPal API Request.
     *
     * @param string $method
     * @param array  $params
     *
     * @throws \Exception
     *
     * @return array|\Psr\Http\Message\StreamInterface
     */
    private function doPayPalRequest($method, $params)
    {
        // Check configuration settings. Reset them if empty.
        if (empty($this->config)) {
            self::setConfig();
        }

        // Throw exception if configuration is still not set.
        if (empty($this->config)) {
            throw new \Exception('PayPal api settings not found.');
        }

        // Setting API Credentials, Version & Method
        $post = [
            'USER'      => $this->config['username'],
            'PWD'       => $this->config['password'],
            'SIGNATURE' => $this->config['signature'],
            'VERSION'   => 123,
            'METHOD'    => $method,
        ];

        // Checking Whether The Request Is PayPal IPN Response
        if ($method == 'verifyipn') {
            unset($post['method']);

            $post_url = $this->config['gateway_url'].'/cgi-bin/webscr';
        } else {
            $post_url = $this->config['api_url'];
        }

        foreach ($params as $key => $value) {
            $post[$key] = $value;
        }

        // Merge $options array if set.
        if (!empty($this->options)) {
            $post = array_merge($post, $this->options);
        }

        try {
            $request = $this->client->post($post_url, [
                'form_params' => $post,
            ]);

            $response = $request->getBody(true);

            return ($method == 'verifyipn') ? $response : $this->retrieveData($response);
        } catch (ClientException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (ServerException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (BadResponseException $e) {
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
