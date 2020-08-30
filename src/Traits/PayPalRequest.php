<?php

namespace Srmklive\PayPal\Traits;

use RuntimeException;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

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
    private $currency;

    /**
     * Additional options for PayPal API request.
     *
     * @var array
     */
    private $options;

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
     * @throws \RuntimeException
     *
     * @return $this
     */
    public function setCurrency($currency = 'USD')
    {
        $allowedCurrencies = ['AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'INR', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD', 'RUB'];

        // Check if provided currency is valid.
        if (!in_array($currency, $allowedCurrencies, true)) {
            throw new RuntimeException('Currency is not supported by PayPal.');
        }

        $this->currency = $currency;

        return $this;
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
        // Set Api Credentials
        if (function_exists('config')) {
            $this->setApiCredentials(
                config('paypal')
            );
        } elseif (!empty($config)) {
            $this->setApiCredentials($config);
        }
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

        $this->setApiProvider($credentials);
    }

    /**
     * Determines which API provider should be used.
     *
     * @param array $credentials
     *
     * @throws \RuntimeException
     */
    private function setApiProvider($credentials)
    {
        if ($this instanceof PayPalClient) {
            $this->setOptions($credentials);

            return;
        }

        throw new RuntimeException('Invalid api credentials provided for PayPal!. Please provide the right api credentials.');
    }
}
