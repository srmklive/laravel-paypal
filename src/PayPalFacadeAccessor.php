<?php namespace Srmklive\PayPal;

use Srmklive\PayPal\Services\AdaptivePayments;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalFacadeAccessor
{
    /**
     * PayPal API provider object
     *
     * @var $provider
     */
    public static $provider;

    /**
     * Get specific PayPal API provider object to use
     *
     * @return ExpressCheckout|AdaptivePayments
     */
    public static function getProvider()
    {
        return self::$provider;
    }

    /**
     * Set specific PayPal API to use
     *
     * @param string $option
     * @return ExpressCheckout|AdaptivePayments
     */
    public static function setProvider($option = '')
    {
        self::$provider = new ExpressCheckout;

        return self::getProvider();
    }
}
