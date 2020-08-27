<?php

namespace Srmklive\PayPal\Services;

use Exception;
use Psr\Http\Message\StreamInterface;
use Srmklive\PayPal\Traits\PayPalRequest as PayPalAPIRequest;

class PayPal
{
    use PayPalAPIRequest;

    /**
     * PayPal constructor.
     *
     * @param string|array $config
     *
     * @throws Exception
     */
    public function __construct($config = '')
    {
        // Setting PayPal API Credentials
        if (is_array($config)) {
            $this->setConfig();
        }

        $this->httpBodyParam = 'form_params';

        $this->options = [];
        $this->options['headers'] = [
            'Accept'            => 'application/json',
            'Accept-Language'   => $this->locale,
        ];
    }

    /**
     * Set ExpressCheckout API endpoints & options.
     *
     * @param array $credentials
     *
     * @return void
     */
    protected function setOptions($credentials)
    {
        // Setting API Endpoints
        if ($this->mode === 'sandbox') {
            $this->config['api_url'] = 'https://api.sandbox.paypal.com';

            $this->config['gateway_url'] = 'https://www.sandbox.paypal.com';
            $this->config['ipn_url'] = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $this->config['api_url'] = 'https://api.paypal.com/';

            $this->config['gateway_url'] = 'https://www.paypal.com';
            $this->config['ipn_url'] = 'https://ipnpb.paypal.com/cgi-bin/webscr';
        }

        // Adding params outside sandbox / live array
        $this->config['payment_action'] = $credentials['payment_action'];
        $this->config['notify_url'] = $credentials['notify_url'];
        $this->config['locale'] = $credentials['locale'];
    }

    /**
     * Login through PayPal API to get access token.
     *
     * @throws \Throwable
     *
     * @return array|StreamInterface|string
     */
    public function getAccessToken()
    {
        $this->apiEndPoint = 'v1/oauth2/token?grant_type=client_credentials';

        $this->options['auth'] = [$this->config['client_id'], $this->config['client_secret']];

        $response = $this->doPayPalRequest();

        if (isset($response['access_token'])) {
            $this->access_token = $response['access_token'];

            if (empty($this->config['app_id'])) {
                $this->config['app_id'] = $response['app_id'];
            }
        }

        return $response;
    }
}
