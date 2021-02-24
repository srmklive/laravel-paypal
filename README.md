# Laravel PayPal

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/srmklive/paypal.svg?style=flat-square)](https://packagist.org/packages/srmklive/paypal)
[![Total Downloads](https://img.shields.io/packagist/dt/srmklive/paypal.svg?style=flat-square)](https://packagist.org/packages/srmklive/paypal)
[![StyleCI](https://github.styleci.io/repos/43671533/shield?branch=v2.0)](https://github.styleci.io/repos/43671533?branch=v2.0)
![Tests](https://github.com/srmklive/laravel-paypal/workflows/TestsV3/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/srmklive/laravel-paypal/badge.svg?branch=v3.0)](https://coveralls.io/github/srmklive/laravel-paypal?branch=v3.0)
[![Code Quality](https://scrutinizer-ci.com/g/srmklive/laravel-paypal/badges/quality-score.png?b=v3.0)](https://scrutinizer-ci.com/g/srmklive/laravel-paypal/?branch=v3.0)

- [Introduction](#introduction)
- [PayPal API Credentials](#paypal-api-credentials)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Support](#support)

    
<a name="introduction"></a>
## Introduction

By using this plugin you can process or refund payments and handle IPN (Instant Payment Notification) from PayPal in your Laravel application.

**This plugin supports the new paypal rest api.**

<a name="paypal-api-credentials"></a>
## PayPal API Credentials

This package uses the new paypal rest api. Refer to this link on how to create API credentials:

https://developer.paypal.com/docs/api/overview/

<a name="installation"></a>
## Installation

* Use following command to install:

If you intend to use ExpressCheckout, please to the following [README](https://github.com/srmklive/laravel-paypal/tree/v1.0). *v2.0* & *v3.0* uses the new rest api.

```bash
composer require srmklive/paypal:~3.0
```

To use this package for Laravel 5.1 to 5.8 use the following commands

```bash
composer require srmklive/paypal:~2.0
```
* Run the following command to publish configuration:

```bash
php artisan vendor:publish --provider "Srmklive\PayPal\Providers\PayPalServiceProvider"
```

<a name="configuration"></a>
## Configuration

* After installation, you will need to add your paypal settings. Following is the code you will find in **config/paypal.php**, which you should update accordingly.

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
        'app_id'            => '',
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
];
```

* Add this to `.env.example` and `.env`

```
#PayPal Setting & API Credentials - sandbox
PAYPAL_SANDBOX_CLIENT_ID=
PAYPAL_SANDBOX_CLIENT_SECRET=

#PayPal Setting & API Credentials - live
PAYPAL_LIVE_CLIENT_ID=
PAYPAL_LIVE_CLIENT_SECRET=
```

<a name="usage"></a>
## Usage

Following are some ways through which you can access the paypal provider:

```php
// Import the class namespaces first, before using it directly
use Srmklive\PayPal\Services\PayPal as PayPalClient;

$provider = new PayPalClient;

// Through facade. No need to import namespaces
$provider = PayPal::setProvider();
```

<a name="usage-paypal-api-configuration"></a>
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

By default the currency used is `USD`. If you wish to change it, you may call `setCurrency` method to set a different currency before calling any respective API methods:

```php
$provider->setCurrency('EUR');
```

## Initiating an order for Checkout
Use the createOrder method to initiate an order
```php
$provider->createOrder([
  "intent"=> "CAPTURE",
  "purchase_units"=> [
      "amount"=> [
        "currency_code"=> "USD",
        "value"=? "100.00"
      ]
  ]
]);
```

The response from this will include an order ID which you will need to retail, and a links collection
so you can direct the user to Paypal to complete the order with their payment details

When the user returns to the notifcation url you can capture the order payment with
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
 
