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
     * @throws \RuntimeException|\Exception
     */
    public function setApiCredentials(array $credentials): void
    {
        if (empty($credentials)) {
            $this->throwConfigurationException();
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
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function setCurrency(string $currency = 'USD'): \Srmklive\PayPal\Services\PayPal
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
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Function to add request header.
     *
     * @param string $key
     * @param string $value
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function setRequestHeader(string $key, string $value): \Srmklive\PayPal\Services\PayPal
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
    public function getRequestHeader(string $key): string
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
     * @throws \Exception
     */
    private function setConfig(array $config): void
    {
        $api_config = function_exists('config') && !empty(config('paypal')) ? config('paypal') : $config;

        // Set Api Credentials
        $this->setApiCredentials($api_config);
    }

    /**
     * Set API environment to be used by PayPal.
     *
     * @param array $credentials
     */
    private function setApiEnvironment(array $credentials): void
    {
        $this->mode = 'live';

        if (!empty($credentials['mode'])) {
            $this->setValidApiEnvironment($credentials['mode']);
        } else {
            $this->throwConfigurationException();
        }
    }

    /**
     * Validate & set the environment to be used by PayPal.
     *
     * @param string $mode
     */
    private function setValidApiEnvironment(string $mode): void
    {
        $this->mode = !in_array($mode, ['sandbox', 'live']) ? 'live' : $mode;
    }

    /**
     * Set configuration details for the provider.
     *
     * @param array $credentials
     *
     * @throws \Exception
     */
    private function setApiProviderConfiguration(array $credentials): void
    {
        // Setting PayPal API Credentials
        if (empty($credentials[$this->mode])) {
            $this->throwConfigurationException();
        }

        $config_params = ['client_id', 'client_secret'];

        foreach ($config_params as $item) {
            if (empty($credentials[$this->mode][$item])) {
                throw new RuntimeException("{$item} missing from the provided configuration. Please add your application {$item}.");
            }
        }

        collect($credentials[$this->mode])->map(function ($value, $key) {
            $this->config[$key] = $value;
        });

        $this->paymentAction = $credentials['payment_action'];

        $this->locale = $credentials['locale'];

        $this->validateSSL = $credentials['validate_ssl'];

        $this->setOptions($credentials);
    }

    /**
     * @throws RuntimeException
     */
    private function throwConfigurationException()
    {
        throw new RuntimeException('Invalid configuration provided. Please provide valid configuration for PayPal API. You can also refer to the documentation at https://srmklive.github.io/laravel-paypal/docs.html to setup correct configuration.');
    }
}
