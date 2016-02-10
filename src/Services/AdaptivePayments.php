<?php namespace Srmklive\PayPal\Services;

use Srmklive\PayPal\Traits\PayPalRequest As PayPalAPIRequest;

class AdaptivePayments
{
    use PayPalAPIRequest;

    /**
     * PayPal Processor Constructor
     */
    public function __construct()
    {
        // Setting PayPal API Credentials
        $this->setConfig();
    }

    /**
     * Function To Set PayPal API Configuration
     */
    private function setConfig()
    {

    }

}
