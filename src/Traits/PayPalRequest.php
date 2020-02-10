<?php

namespace Srmklive\PayPal\Traits;

use Exception;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Collection;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Srmklive\PayPal\Services\AdaptivePayments;
use Srmklive\PayPal\Services\ExpressCheckout;

trait PayPalRequest
{
    use PayPalHttpClient;

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
     * PayPal API Certificate data for authentication.
     *
     * @var string
     */
    private $certificate;

    /**
     * PayPal API mode to be used.
     *
     * @var string
     */
    public $mode;

    /**
     * Request data to be sent to PayPal.
     *
     * @var Collection
     */
    protected $post;

    /**
     * PayPal API configuration.
     *
     * @var array
     */
    private $config;

    /**
     * Item subtotal.
     *
     * @var float
     */
    private $subtotal;

    /**
     * Default currency for PayPal.
     *
     * @var string
     */
    private $currency;

    /**
     * Default billing type for PayPal reference transactions.
     *
     * @var string
     */
    private $billingType;

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
     * Validate SSL details when creating HTTP client.
     *
     * @var bool
     */
    private $validateSSL;

    /**
     * Set PayPal API Credentials.
     *
     * @param array $credentials
     *
     * @throws Exception
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

        // Set default billing type
        $this->setBillingType($credentials['billing_type']);

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
     * @throws Exception
     *
     * @return $this
     */
    public function setCurrency($currency = 'USD')
    {
        $allowedCurrencies = ['AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'INR', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD', 'RUB'];

        // Check if provided currency is valid.
        if (!in_array($currency, $allowedCurrencies, true)) {
            throw new Exception('Currency is not supported by PayPal.');
        }

        $this->currency = $currency;

        return $this;
    }

    /**
     * Function to set billing type.
     *
     * @param string $billingType
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setBillingType($billingType = 'MerchantInitiatedBilling')
    {
        $allowedBillingTypes = ['MerchantInitiatedBilling', 'MerchantInitiatedBillingSingleAgreement', 'RecurringPayments'];

        if ($billingType !== null && !in_array($billingType, $allowedBillingTypes, true)) {
            throw new RuntimeException('Billing type is not supported by PayPal.');
        }

        $this->billingType = $billingType;

        return $this;
    }

    /**
     * Retrieve PayPal IPN Response.
     *
     * @param array $post
     *
     * @throws Exception
     *
     * @return array
     */
    public function verifyIPN($post)
    {
        $this->setRequestData($post);

        $this->apiUrl = $this->config['ipn_url'];

        return $this->doPayPalRequest('verifyipn');
    }

    /**
     * Setup request data to be sent to PayPal.
     *
     * @param array $data
     *
     * @return Collection
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

        $this->setRequestData();
    }

    /**
     * Set default values for configuration.
     *
     * @return void
     */
    private function setDefaultValues()
    {
        // Set default payment action.
        if (empty($this->paymentAction)) {
            $this->paymentAction = 'Sale';
        }

        // Set default locale.
        if (empty($this->locale)) {
            $this->locale = 'en_US';
        }

        // Set default value for SSL validation.
        if (empty($this->validateSSL)) {
            $this->validateSSL = false;
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

        // Setup PayPal API Signature value to use.
        $this->config['signature'] = empty($this->config['certificate']) ?
        $this->config['secret'] : $this->config['certificate'];

        $this->paymentAction = $credentials['payment_action'];

        $this->locale = $credentials['locale'];

        $this->certificate = $this->config['certificate'];

        $this->validateSSL = $credentials['validate_ssl'];

        $this->setApiProvider($credentials);
    }

    /**
     * Determines which API provider should be used.
     *
     * @param array $credentials
     *
     * @throws Exception
     */
    private function setApiProvider($credentials)
    {
        if ($this instanceof AdaptivePayments) {
            return $this->setAdaptivePaymentsOptions();
        }

        if ($this instanceof ExpressCheckout) {
            return $this->setExpressCheckoutOptions($credentials);
        }

        throw new RuntimeException('Invalid api credentials provided for PayPal!. Please provide the right api credentials.');
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
     * Parse PayPal NVP Response.
     *
     * @param string                $method
     * @param array|StreamInterface $response
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
