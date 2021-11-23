# Laravel PayPal

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/srmklive/paypal.svg?style=flat-square)](https://packagist.org/packages/srmklive/paypal)
[![Total Downloads](https://img.shields.io/packagist/dt/srmklive/paypal.svg?style=flat-square)](https://packagist.org/packages/srmklive/paypal)
[![StyleCI](https://github.styleci.io/repos/43671533/shield?branch=v2.0)](https://github.styleci.io/repos/43671533?branch=v2.0)
![Tests](https://github.com/srmklive/laravel-paypal/workflows/TestsV3/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/srmklive/laravel-paypal/badge.svg?branch=v3.0)](https://coveralls.io/github/srmklive/laravel-paypal?branch=v3.0)
[![Code Quality](https://scrutinizer-ci.com/g/srmklive/laravel-paypal/badges/quality-score.png?b=v3.0)](https://scrutinizer-ci.com/g/srmklive/laravel-paypal/?branch=v3.0)

- [Documentation](#introduction)
- [Usage](#usage)
- [Support](#support)

    
<a name="introduction"></a>
## Documentation

The documentation for the package can be viewed by clicking the following link:

[https://srmklive.github.io/laravel-paypal/docs.html](https://srmklive.github.io/laravel-paypal/docs.html)

<a name="usage"></a>
## Usage

Following are some ways through which you can access the paypal provider:

```php
// Import the class namespaces first, before using it directly
use Srmklive\PayPal\Services\PayPal as PayPalClient;

$provider = new PayPalClient;

// Through facade. No need to import namespaces
$provider = \PayPal::setProvider();
```

<a name="usage-paypal-api-configuration"></a>
## Configuration File

The configuration file **paypal.php** is located in the **config** folder. Following are its contents when published:

```php
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
        'app_id'            => env('PAYPAL_LIVE_APP_ID', ''),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
];
```

## Override PayPal API Configuration

You can override PayPal API configuration by calling `setApiCredentials` method:

```php
$provider->setApiCredentials($config);
```


<a name="usage-paypal-get-access-token"></a>
## Get Access Token

After setting the PayPal API configuration by calling `setApiCredentials` method. You need to get access token before performing any API calls

```php
$provider->getAccessToken();
```


<a name="usage-currency"></a>
## Set Currency

By default, the currency used is `USD`. If you wish to change it, you may call `setCurrency` method to set a different currency before calling any respective API methods:

```php
$provider->setCurrency('EUR');
```

## Initiating an order for Checkout
Use the createOrder method to initiate an order
```php
$provider->createOrder([
  "intent"=> "CAPTURE",
  "purchase_units"=> [
      0 => [
          "amount"=> [
              "currency_code"=> "USD",
              "value"=> "100.00"
          ]
      ]
  ]
]);
```

The response from this will include an order ID which you will need to retail, and a links collection
so you can redirect the user to Paypal to complete the order with their payment details

When the user returns to the notification url you can capture the order payment with
```php
$provider->capturePaymentOrder($order_id); //order id from the createOrder step 
```

<a name="support"></a>
## Support

This version supports Laravel 6 or greater.
* In case of any issues, kindly create one on the [Issues](https://github.com/srmklive/laravel-paypal/issues) section.
* If you would like to contribute:
  * Fork this repository.
  * Implement your features.
  * Generate pull request.
 
