<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>
 */

return [
    'mode' => 'sandbox', // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'username' => '',
        'password' => '',
        'secret' => '',
        'certificate' => '',
        'app_id' => 'APP-80W284485P519543T',    // Used for testing Adaptive Payments API in sandbox mode
    ],
    'live' => [
        'username' => '',
        'password' => '',
        'secret' => '',
        'certificate' => '',
        'app_id' => '',         // Used for Adaptive Payments API 
    ],

    'payment_action' => 'Sale', // Can Only Be 'Sale', 'Authorization', 'Order'
    'currency' => 'USD',
    'notify_url' => '', // Change this accordingly for your application.
];
