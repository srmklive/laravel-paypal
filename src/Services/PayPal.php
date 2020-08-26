<?php

namespace Srmklive\PayPal\Services;

use Exception;
use Illuminate\Support\Collection;
use Psr\Http\Message\StreamInterface;
use Srmklive\PayPal\Traits\PayPalRequest as PayPalAPIRequest;

class PayPal
{
    use PayPalAPIRequest;

    /**
     * ExpressCheckout constructor.
     *
     * @param array $config
     *
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        // Setting PayPal API Credentials
        $this->setConfig($config);

        $this->httpBodyParam = 'form_params';

        $this->options = [];
    }

    /**
     * Set ExpressCheckout API endpoints & options.
     *
     * @param array $credentials
     *
     * @return void
     */
    public function setOptions($credentials)
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
}
