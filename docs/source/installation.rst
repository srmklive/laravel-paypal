
Installation
============

This package utilizes PayPal Rest API under the hood. If you are using PayPal Express Checkout, please refer to the README for v1 here:

https://github.com/srmklive/laravel-paypal/blob/v1.0/README.md


For installation, run the following commands:

Laravel 5.1 to 5.8
------------------

.. code-block:: console

    composer require srmklive/paypal:~2.0

Laravel 6 & above
-----------------

.. code-block:: console

    composer require srmklive/paypal:~3.0


Publishing Assets
=================

After installation, you can use the following commands to publish the assets:

.. code-block:: console

    php artisan vendor:publish --provider "Srmklive\PayPal\Providers\PayPalServiceProvider"


Configuration
=============

.. code-block:: console

    # PayPal API Mode
    # Values: sandbox or live (Default: live)
    PAYPAL_MODE=

    #PayPal Setting & API Credentials - sandbox
    PAYPAL_SANDBOX_CLIENT_ID=
    PAYPAL_SANDBOX_CLIENT_SECRET=

    #PayPal Setting & API Credentials - live
    PAYPAL_LIVE_CLIENT_ID=
    PAYPAL_LIVE_CLIENT_SECRET=
    PAYPAL_LIVE_APP_ID=

    # Payment Action. Can only be 'Sale', 'Authorization' or 'Order'
    PAYPAL_PAYMENT_ACTION=Sale

    # Currency. Default is USD. If you need to update it, then set the value through the PAYPAL_CURRENCY environment variable.
    PAYPAL_CURRENCY=EUR

    # Validate SSL when creating api client. By default, the value is great. To disable validation set to false.
    PAYPAL_VALIDATE_SSL=false


Configuration File
==================

The configuration file **paypal.php** is located in the **config** folder. Following are its contents when published:

.. code-block:: console

    return [
        'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
        'sandbox' => [
            'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
            'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
            'app_id'            => 'APP-80W284485P519543T',
        ],
        'live' => [
            'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', ''),
            'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
            'app_id'            => '',
        ],

        'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
        'currency'       => env('PAYPAL_CURRENCY', 'USD'),
        'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
        'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
        'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
    ];    