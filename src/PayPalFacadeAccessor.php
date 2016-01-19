<?php namespace Srmklive\PayPal;

use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalFacadeAccessor
{
    public static function getProvider()
    {
        return new ExpressCheckout;
    }
}
