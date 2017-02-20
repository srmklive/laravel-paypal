# Laravel PayPal

[![StyleCI](https://styleci.io/repos/43671533/shield?style=flat)](https://styleci.io/repos/43671533)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/srmklive/paypal.svg?style=flat-square)](https://packagist.org/packages/srmklive/paypal)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/srmklive/paypal.svg?style=flat-square)](https://packagist.org/packages/srmklive/paypal)

- [Introduction](#introduction)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Set Currency] (#usage-currency)
  - [Additional PayPal API Parameters] (#usage-paypal-params)
  - [Express Checkout] (#usage-express-checkout)
    - [SetExpressCheckout] (#usage-ec-setexpresscheckout)
    - [GetExpressCheckoutDetails] (#usage-ec-getexpresscheckoutdetails)
    - [DoExpressCheckoutPayment] (#usage-ec-doexpresscheckoutpayment)
    - [RefundTransaction] (#usage-ec-refundtransaction)
    - [CreateBillingAgreement] (#usage-ec-createbillingagreement)
    - [CreateRecurringPaymentsProfile] (#usage-ec-createrecurringprofile)
    - [GetRecurringPaymentsProfileDetails] (#usage-ec-getrecurringprofiledetails)
    - [UpdateRecurringPaymentsProfile] (#usage-ec-updaterecurringprofile)
    - [ManageRecurringPaymentsProfileStatus] (#usage-ec-managerecurringprofile)
  - [Adaptive Payments] (#usage-adaptive-payments)
    - [Pay] (#usage-adaptive-pay)
- [Handling PayPal IPN](#paypalipn)
- [Support](#support)

<a name="introduction"></a>
## Introduction

Laravel plugin For Processing Payments Through Paypal. Using this plugin you can process or refund payments and handle IPN (Instant Payment Notification) from PayPal in your Laravel application.

**Currently only PayPal Express Checkout API Is Supported.**


<a name="installation"></a>
## Installation

* Use following command to install:

```bash
composer require srmklive/paypal:~1.0
```

* Add the service provider to your `$providers` array in `config/app.php` file like: 

```php
'Srmklive\PayPal\Providers\PayPalServiceProvider' // Laravel 5
```
```php
Srmklive\PayPal\Providers\PayPalServiceProvider::class // Laravel 5.1 or greater
```

* Add the alias to your `$aliases` array in `config/app.php` file like: 

```php
'PayPal' => 'Srmklive\PayPal\Facades\PayPal' // Laravel 5
```
```php
'PayPal' => Srmklive\PayPal\Facades\PayPal::class // Laravel 5.1 or greater
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
    'mode' => 'sandbox',        // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'username' => '',       // Api Username
        'password' => '',       // Api Password
        'secret' => '',         // This refers to api signature
        'certificate' => '',    // Link to paypals cert file, storage_path('cert_key_pem.txt')
    ],
    'live' => [
        'username' => '',       // Api Username
        'password' => '',       // Api Password
        'secret' => '',         // This refers to api signature
        'certificate' => '',    // Link to paypals cert file, storage_path('cert_key_pem.txt')
    ],
    'payment_action' => 'Sale', // Can Only Be 'Sale', 'Authorization', 'Order'
    'currency' => 'USD',
    'notify_url' => '',         // Change this accordingly for your application.
];
```

<a name="usage"></a>
## Usage

Following are some ways through which you can access the paypal provider:

```php
// Import the class namespaces first, before using it directly
use Srmklive\PayPal\Services\ExpressCheckout;
use Srmklive\PayPal\Services\AdaptivePayments;

$provider = new ExpressCheckout;      // To use express checkout.
$provider = new AdaptivePayments;     // To use adaptive payments.

// Through facade. No need to import namespaces
$provider = PayPal::setProvider('express_checkout');      // To use express checkout(used by default).
$provider = PayPal::setProvider('adaptive_payments');     // To use adaptive payments.
 
// Through global helper functions
$provider = express_checkout();      // To use express checkout.
$provider = adaptive_payments();     // To use adaptive payments. 
```


<a name="usage-currency"></a>
## Set Currency

By default the currency used is `USD`. If you wish to change it, you may call `setCurrency` method to set a different currency before calling any respective API methods:

```php
$provider->setCurrency('EUR')->setExpressCheckout($data);
```


<a name="usage-paypal-params"></a>
## Additional PayPal API Parameters

By default only a specific set of parameters are used for PayPal API calls. However, if you wish specify any other additional parameters you may call the `addOptions` method before calling any respective API methods:

```php
$options = [
    'BRANDNAME' => 'MyBrand',
    'LOGOIMG' => 'https://example.com/mylogo.png',
    'CHANNELTYPE' => 'Merchant'
];

$provider->addOptions($options)->setExpressCheckout($data);
```

<a name="usage-express-checkout"></a>
#### Express Checkout

```php
$data = [];
$data['items'] = [
    [
        'name' => 'Product 1',
        'price' => 9.99,
        'qty' => 1
    ],
    [
        'name' => 'Product 2',
        'price' => 4.99,
        'qty' => 2
    ]
];

$data['invoice_id'] = 1;
$data['invoice_description'] = "Order #{$data[invoice_id]} Invoice";
$data['return_url'] = url('/payment/success');
$data['cancel_url'] = url('/cart');

$total = 0;
foreach($data['items'] as $item) {
    $total += $item['price']*$item['qty'];
}

$data['total'] = $total;
```

<a name="usage-ec-setexpresscheckout"></a>
* **SetExpressCheckout**

    ```php
    $response = $provider->setExpressCheckout($data);
    
    // Use the following line when creating recurring payment profiles (subscriptions)
    $response = $provider->setExpressCheckout($data, true);
    
     // This will redirect user to PayPal
    return redirect($response['paypal_link']);
    ```

<a name="usage-ec-getexpresscheckoutdetails"></a>
* **GetExpressCheckoutDetails**

    ```php
    $response = $provider->getExpressCheckoutDetails($token);
    ```
    
<a name="usage-ec-doexpresscheckoutpayment"></a>
* **DoExpressCheckoutPayment** 

    ```php
    // Note that 'token', 'PayerID' are values returned by PayPal when it redirects to success page after successful verification of user's PayPal info.
    $response = $provider->doExpressCheckoutPayment($data, $token, $PayerID);
    ```

<a name="usage-ec-refundtransaction"></a>
* **RefundTransaction**

    ```php
    $response = $provider->refundTransaction($transactionid);
    ```

<a name="usage-ec-createbillingagreement"></a>    
* **CreateBillingAgreement**

    ```php
    // The $token is the value returned from SetExpressCheckout API call
    $response = $provider->createBillingAgreement($token);
    ```    

<a name="usage-ec-createrecurringprofile"></a>
* **CreateRecurringPaymentsProfile**

    ```php
    // The $token is the value returned from SetExpressCheckout API call
    $startdate = Carbon::now()->toAtomString();
    $profile_desc = !empty($data['subscription_desc']) ?
                $data['subscription_desc'] : $data['invoice_description'];
    $data = [
        'PROFILESTARTDATE' => $startdate,
        'DESC' => $profile_desc,
        'BILLINGPERIOD' => 'Month', // Can be 'Day', 'Week', 'SemiMonth', 'Month', 'Year'
        'BILLINGFREQUENCY' => 12, // set 12 for monthly, 52 for yearly
        'AMT' => 10, // Billing amount for each billing cycle
        'CURRENCYCODE' => 'USD', // Currency code 
        'TRIALBILLINGPERIOD' => 'Day',  // (Optional) Can be 'Day', 'Week', 'SemiMonth', 'Month', 'Year'
        'TRIALBILLINGFREQUENCY' => 10, // (Optional) set 12 for monthly, 52 for yearly 
        'TRIALTOTALBILLINGCYCLES' => 1, // (Optional) Change it accordingly
        'TRIALAMT' => 0, // (Optional) Change it accordingly
    ];
    $response = $provider->createRecurringPaymentsProfile($data, $token);
    ```    

<a name="usage-ec-getrecurringprofiledetails"></a>
* **GetRecurringPaymentsProfileDetails**

    ```php
    $response = $provider->getRecurringPaymentsProfileDetails($profileid);
    ```    

<a name="usage-ec-updaterecurringprofile"></a>
* **UpdateRecurringPaymentsProfile**

    ```php
    $response = $provider->updateRecurringPaymentsProfile($data, $profileid);
    ```    

<a name="usage-ec-managerecurringprofile"></a>
* **ManageRecurringPaymentsProfileStatus**

    ```php
    // Cancel recurring payment profile
    $response = $provider->cancelRecurringPaymentsProfile($profileid);
    
    // Suspend recurring payment profile
    $response = $provider->suspendRecurringPaymentsProfile($profileid);
    
    // Reactivate recurring payment profile
    $response = $provider->reactivateRecurringPaymentsProfile($profileid);    
    ```    

<a name="usage-adaptive-payments"></a>
#### Adaptive Payments

To use adaptive payments, you must set the provider to use Adaptive Payments:

```php
PayPal::setProvider('adaptive_payments');
```

<a name="usage-adaptive-pay"></a>
* **Pay**

```php

// Change the values accordingly for your application
$data = [
    'receivers'  => [
        [
            'email' => 'johndoe@example.com',
            'amount' => 10,
            'primary' => true,
        ],
        [
            'email' => 'janedoe@example.com',
            'amount' => 5,
            'primary' => false
        ]
    ],
    'payer' => 'EACHRECEIVER', // (Optional) Describes who pays PayPal fees. Allowed values are: 'SENDER', 'PRIMARYRECEIVER', 'EACHRECEIVER' (Default), 'SECONDARYONLY'
    'return_url' => url('payment/success'), 
    'cancel_url' => url('payment/cancel'),
];

$response = $provider->createPayRequest($data);

// The above API call will return the following values if successful:
// 'responseEnvelope.ack', 'payKey', 'paymentExecStatus'

```

Next, you need to redirect the user to PayPal to authorize the payment

```php
$redirect_url = $provider->getRedirectUrl('approved', $response['payKey']);

return redirect($redirect_url);
```

<a name="paypalipn"></a>
## Handling PayPal IPN
You can also handle Instant Payment Notifications from PayPal.
Suppose you have set IPN URL to **http://example.com/ipn/notify/** in PayPal. To handle IPN you should do the following:

* First add the `ipn/notify` tp your routes file:

    ```php
    Route::post('ipn/notify','PayPalController@postNotify'); // Change it accordingly in your application
    ```
          
* Open `App\Http\Middleware\VerifyCsrfToken.php` and add your IPN route to `$excluded` routes variable.

    ```php
    'ipn/notify'
    ```

* Then in the controller where you are handling IPN, write the following:

    ```
    // Put this above controller definition
    use Srmklive\PayPal\Traits\IPNResponse As PayPalIPN;
    
    // Then add the following before function declaration
    use PayPalIPN;
    ```
    
* Write the following code in the function where you will parse IPN response:    
    
    ```php
    /**
     * Retrieve IPN Response From PayPal
     *
     * @param \Illuminate\Http\Request $request
     */
    public function postNotify(Request $request)
    {
        $response = $this->parsePayPalIPN($request);
        
        if ($response === 'VERIFIED') {                      
            // Your code goes here ...
        }                            
    }        
    ```
            
<a name="support"></a>
## Support

This plugin only supports Laravel 5 or greater.
* In case of any issues, kindly create one on the [Issues](https://github.com/srmklive/laravel-paypal/issues) section.
* If you would like to contribute:
  * Fork this repository.
  * Implement your features.
  * Generate pull request.
 
