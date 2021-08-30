<?php

namespace Srmklive\PayPal\Traits;

use RuntimeException;

trait PayPalRequest
{
    use PayPalHttpClient;
    use PayPalAPI;

    /**
     * PayPal API mode to be used.
     *
     * @var string
     */
    public $mode;

    /**
     * PayPal access token.
     *
     * @var string
     */
    protected $access_token;

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
    protected $currency;

    /**
     * Additional options for PayPal API request.
     *
     * @var array
     */
    protected $options;

    /**
     * Set PayPal API Credentials.
     *
     * @param array $credentials
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function setApiCredentials($credentials)
    {
        if (empty($credentials)) {
            throw new RuntimeException('Empty configuration provided. Please provide valid configuration for Express Checkout API.');
        }

        // Setting Default PayPal Mode If not set
        $this->setApiEnvironment($credentials);

        // Set API configuration for the PayPal provider
        $this->setApiProviderConfiguration($credentials);

        // Set default currency.
        $this->setCurrency($credentials['currency']);

        // Set Http Client configuration.
        $this->setHttpClientConfiguration();
    }

    /**
     * Function to set currency.
     *
     * @param string $currency
     *
     * @throws \RuntimeException
     *
     * @return $this
     */
    public function setCurrency($currency = 'USD')
    {
        $allowedCurrencies = ['AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'INR', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD', 'RUB', 'CNY'];

        // Check if provided currency is valid.
        if (!in_array($currency, $allowedCurrencies, true)) {
            throw new RuntimeException('Currency is not supported by PayPal.');
        }

        $this->currency = $currency;

        return $this;
    }

    /**
     * Return the set currency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Function to add request header.
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function setRequestHeader($key, $value)
    {
        $this->options['headers'][$key] = $value;

        return $this;
    }

    /**
     * Return request options header.
     *
     * @param string $key
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function getRequestHeader($key)
    {
        if (isset($this->options['headers'][$key])) {
            return $this->options['headers'][$key];
        }

        throw new RuntimeException('Options header is not set.');
    }

    /**
     * Function To Set PayPal API Configuration.
     *
     * @param array $config
     *
     * @throws Exception
     */
    private function setConfig(array $config = [])
    {
        $api_config = function_exists('config') ? config('paypal') : $config;

        // Set Api Credentials
        $this->setApiCredentials($api_config);
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
        $this->mode = 'live';

        if (!empty($credentials['mode'])) {
            $this->setValidApiEnvironment($credentials['mode']);
        }
    }

    /**
     * Validate & set the environment to be used by PayPal.
     *
     * @param string $mode
     *
     * @return void
     */
    private function setValidApiEnvironment($mode)
    {
        $this->mode = !in_array($mode, ['sandbox', 'live']) ? 'live' : $mode;
    }

    /**
     * Set configuration details for the provider.
     *
     * @param array $credentials
     *
     * @throws Exception
     *
     * @return void
     */
    private function setApiProviderConfiguration($credentials)
    {
        // Setting PayPal API Credentials
        collect($credentials[$this->mode])->map(function ($value, $key) {
            $this->config[$key] = $value;
        });

        $this->paymentAction = $credentials['payment_action'];

        $this->locale = $credentials['locale'];

        $this->validateSSL = $credentials['validate_ssl'];

        $this->setOptions($credentials);
    }
}
