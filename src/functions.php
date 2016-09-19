<?php

if (!function_exists('express_checkout')) {
    /**
     * Global helper function for Express Checkout class.
     *
     * @return mixed
     */
    function express_checkout()
    {
        return app('express_checkout');
    }
}

if (!function_exists('adaptive_payments')) {
    /**
     * Global helper function for Adaptive Payments class.
     *
     * @return mixed
     */
    function adaptive_payments()
    {
        return app('adaptive_payments');
    }
}
