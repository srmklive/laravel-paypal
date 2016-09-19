<?php

namespace Srmklive\PayPal\Facades;

/*
 * Class Facade
 * @package Srmklive\PayPal\Facades
 * @see Srmklive\PayPal\ExpressCheckout
 */

use Illuminate\Support\Facades\Facade;

class PayPal extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Srmklive\PayPal\PayPalFacadeAccessor';
    }
}
