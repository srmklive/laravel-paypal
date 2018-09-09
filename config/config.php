<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => 'sandbox', // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'client'    => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
        'secret'    => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
        'app_id'    => 'APP-80W284485P519543T', // Used for testing Adaptive Payments API in sandbox mode
    ],
    'live' => [
        'client'    => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'secret'    => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
        'app_id'    => '', // Used for Adaptive Payments API
    ],
    'locale'        => 'en_US',
    'validate_ssl'  => env('PAYPAL_VALIDATE_SSL', true),
];
