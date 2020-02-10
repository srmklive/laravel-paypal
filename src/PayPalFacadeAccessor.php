<?php

namespace Srmklive\PayPal;

use Exception;
use Srmklive\PayPal\Services\AdaptivePayments;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalFacadeAccessor
{
    /**
     * PayPal API provider object.
     *
     * @var
     */
    public static $provider;

    /**
     * Get specific PayPal API provider object to use.
     *
     * @return ExpressCheckout|AdaptivePayments
     * @throws Exception
     */
    public static function getProvider()
    {
        if (empty(self::$provider)) {
            return new ExpressCheckout();
        }

        return self::$provider;
    }

    /**
     * Set specific PayPal API to use.
     *
     * @param string $option Defaults to express_checkout
     *
     * @return ExpressCheckout|AdaptivePayments
     * @throws Exception
     */
    public static function setProvider($option = 'express_checkout')
    {
        // Set default provider. Defaults to ExpressCheckout
        if (empty($option) || $option === 'express_checkout' || $option !== 'adaptive_payments') {
            self::$provider = new ExpressCheckout();
        } else {
            self::$provider = new AdaptivePayments();
        }

        return self::getProvider();
    }
}
