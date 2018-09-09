<?php

namespace Srmklive\PayPal\Services;

use Srmklive\PayPal\Traits\PayPalHttpClient;
use Srmklive\PayPal\Traits\PayPalLocales;

class PayPalRestAPI
{
    use PayPalHttpClient, PayPalLocales;

    /**
     * @var array
     */
    protected $curlConfig;

    /**
     * @var bool
     */
    public $sandbox;

    /**
     * @var array
     */
    protected $credentials;

    /**
     * @var string
     */
    protected $endpoints;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var array
     */
    protected $response;

    /**
     * PayPalRestAPI constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $mode = config('paypal.mode');
        if (empty($mode) || !in_array($mode, ['sandbox', 'live'])) {
            $mode = 'live';
        }

        $sandbox = ($mode === 'sandbox') ? true : false;
        if (function_exists('config')) {
            $credentials = config('paypal.' . $mode);

            $this->setApiCredentials($credentials, $sandbox);
        }
    }

    /**
     * Set PayPal Rest API credentials & endpoints.
     *
     * @param array $credentials
     * @param bool $sandbox
     *
     * @throws \Exception
     */
    public function setApiCredentials($credentials, $sandbox)
    {
        if (!empty($credentials['locale'])) {
            $this->setLocale($credentials['locale']);
        }

        $this->credentials = $credentials;
        $this->sandbox = $sandbox;

        if ($this->sandbox === true) {
            $this->endpoints = [
                'rest' => 'https://api.sandbox.paypal.com/',
                'redirect' => 'https://www.sandbox.paypal.com',
            ];
        } else {
            $this->endpoints = [
                'rest' => 'https://api.paypal.com/',
                'redirect' => 'https://www.paypal.com',
            ];
        }

        $this->setHttpClientConfiguration();

        $this->accessToken();
    }

    /**
     * Set default locale.
     *
     * @param string $locale
     *
     * @throws \Exception
     */
    public function setLocale($locale)
    {
        if (!in_array($locale, $this->locales())) {
            throw new \Exception("Invalid locale");
        }

        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @throws \Exception
     */
    protected function accessToken()
    {
        $this->apiUrl = $this->endpoints['rest'].'v1/oauth2/token';

        $this->params = [
            'auth' => [
                $this->credentials['client'],
                $this->credentials['secret'],
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => $this->locale
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ]
        ];

        $this->setResponse(
            $this->sendRequest()
        );

        $this->accessToken = $this->response['access_token'];
    }

    /**
     * @param array $response
     */
    protected function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }
}