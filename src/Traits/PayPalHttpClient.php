<?php

namespace Srmklive\PayPal\Traits;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Throwable;

trait PayPalHttpClient
{
    /**
     * Http Client class object.
     *
     * @var HttpClient
     */
    private $client;

    /**
     * Http Client configuration.
     *
     * @var array
     */
    private $httpClientConfig;

    /**
     * PayPal API Endpoint.
     *
     * @var string
     */
    private $apiUrl;

    /**
     * PayPal API Endpoint.
     *
     * @var string
     */
    private $apiEndPoint;

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
     * Validate SSL details when creating HTTP client.
     *
     * @var bool
     */
    private $validateSSL;

    /**
     * Request type.
     *
     * @var string
     */
    protected $verb = 'post';

    /**
     * Set curl constants if not defined.
     *
     * @return void
     */
    protected function setCurlConstants()
    {
        $constants = [
            'CURLOPT_SSLVERSION'        => 32,
            'CURL_SSLVERSION_TLSv1_2'   => 6,
            'CURLOPT_SSL_VERIFYPEER'    => 64,
            'CURLOPT_SSLCERT'           => 10025,
        ];

        foreach ($constants as $key => $value) {
            if (!defined($key)) {
                define($key, $constants[$key]);
            }
        }
    }

    /**
     * Function to initialize Http Client.
     *
     * @return void
     */
    protected function setClient()
    {
        $this->client = new HttpClient([
            'curl' => $this->httpClientConfig,
        ]);
    }

    /**
     * Function to set Http Client configuration.
     *
     * @return void
     */
    protected function setHttpClientConfiguration()
    {
        $this->setCurlConstants();

        $this->httpClientConfig = [
            CURLOPT_SSLVERSION     => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_SSL_VERIFYPEER => $this->validateSSL,
        ];

        // Initialize Http Client
        $this->setClient();

        // Set default values.
        $this->setDefaultValues();

        // Set PayPal API Endpoint.
        $this->apiUrl = $this->config['api_url'];

        // Set PayPal IPN Notification URL
        $this->notifyUrl = $this->config['notify_url'];
    }

    /**
     * Set default values for configuration.
     *
     * @return void
     */
    private function setDefaultValues()
    {
        $defaults = [
            'paymentAction' => 'Sale',
            'locale'        => 'en_US',
            'validateSSL'   => true,
        ];

        foreach ($defaults as $key => $value) {
            if (empty($this->$key)) {
                $this->$key = $defaults[$key];
            }
        }
    }

    /**
     * Perform PayPal API request & return response.
     *
     * @throws \Throwable
     *
     * @return StreamInterface
     */
    private function makeHttpRequest()
    {
        try {
            return $this->client->{$this->verb}(
                $this->apiUrl,
                $this->options
            )->getBody();
        } catch (Throwable $t) {
            throw new RuntimeException($t->getRequest().' '.$t->getResponse());
        }
    }

    /**
     * Function To Perform PayPal API Request.
     *
     * @throws \Throwable
     *
     * @return array|StreamInterface
     */
    private function doPayPalRequest()
    {
        try {
            // Perform PayPal HTTP API request.
            $response = $this->makeHttpRequest();

            return \GuzzleHttp\json_decode($response, true);
        } catch (Throwable $t) {
            $message = collect($t->getTrace())->implode('\n');
        }

        return [
            'type'    => 'error',
            'message' => $message,
        ];
    }
}
