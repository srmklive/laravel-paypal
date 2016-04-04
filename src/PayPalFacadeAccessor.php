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
        if (empty(self::$provider))
            return new ExpressCheckout;
        else
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
        if (!in_array($option, ['express_checkout', 'adaptive_payments']))
            $option = 'express_checkout';

        if ($option == 'express_checkout')
            self::$provider = new ExpressCheckout;
        elseif ($option == 'adaptive_payments')
            self::$provider = new AdaptivePayments;

        return self::getProvider();
    }
}
