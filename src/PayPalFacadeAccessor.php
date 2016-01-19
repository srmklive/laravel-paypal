<?php namespace Srmklive\PayPal;

use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalFacadeAccessor
{
    public function getProvider()
    {
        return new ExpressCheckout;
    }
}
