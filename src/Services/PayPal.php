<?php

namespace Srmklive\PayPal\Services;

use Exception;
use Srmklive\PayPal\Traits\PayPalRequest as PayPalAPIRequest;

class PayPal
{
    use PayPalAPIRequest;

    /**
     * PayPal constructor.
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
        $this->options['headers'] = [
            'Accept'            => 'application/json',
            'Accept-Language'   => $this->locale,
        ];
    }

    /**
     * Set ExpressCheckout API endpoints & options.
     *
     * @param array $credentials
     */
    protected function setOptions(array $credentials): void
    {
        // Setting API Endpoints
        $this->config['api_url'] = 'https://api-m.paypal.com';

        $this->config['gateway_url'] = 'https://www.paypal.com';
        $this->config['ipn_url'] = 'https://ipnpb.paypal.com/cgi-bin/webscr';

        if ($this->mode === 'sandbox') {
            $this->config['api_url'] = 'https://api-m.sandbox.paypal.com';

            $this->config['gateway_url'] = 'https://www.sandbox.paypal.com';
            $this->config['ipn_url'] = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
        }

        // Adding params outside sandbox / live array
        $this->config['payment_action'] = $credentials['payment_action'];
        $this->config['notify_url'] = $credentials['notify_url'];
        $this->config['locale'] = $credentials['locale'];
    }
}
