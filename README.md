# Laravel PayPal

- [Introduction](#introduction)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
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

```
composer require srmklive/paypal
```

* Add the service provider to your $providers array in config/app.php file like: 

```
'Srmklive\PayPal\Providers\PayPalServiceProvider' // Laravel 5
```
```
Srmklive\PayPal\Providers\PayPalServiceProvider::class // Laravel 5.1 or greater
```

* Add the alias to your $aliases array in config/app.php file like: 

```
'PayPal' => 'Srmklive\PayPal\Facades\PayPal' // Laravel 5
```
```
'PayPal' => Srmklive\PayPal\Facades\PayPal::class // Laravel 5.1 or greater
```

* Run the following command to publish configuration:

```
php artisan vendor:publish
```


## Configuration

* After installation, you will need to add your paypal settings. Following is the code you will find in **config/paypal.php**, which you should update accordingly.

```
return [
    'mode' => 'sandbox', // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'username' => '',
        'password' => '',
        'secret' => '',
        'certificate' => '',
    ],
    'live' => [
        'username' => '',
        'password' => '',
        'secret' => '',
        'certificate' => '',
    ],
    'payment_action' => 'Sale', // Can Only Be 'Sale', 'Authorization', 'Order'
    'currency' => 'USD',
    'notify_url' => '', // Change this accordingly for your application.
];
```

## Usage

Following are some ways through which you can access the paypal provider:

```
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

<a name="usage-express-checkout"></a>
#### Express Checkout

```
$data = [];
$data['items'] = [
    [
        'name' => 'Product 1',
        'price' => 9.99
    ],
    [
        'name' => 'Product 2',
        'price' => 4.99
    ]
];

$data['invoice_id'] = 1;
$data['invoice_description'] = "Order #$data[invoice_id] Invoice";
$data['return_url'] = url('/payment/success');
$data['cancel_url'] = url('/cart');

$total = 0;
foreach($data['items'] as $item) {
    $total += $item['price'];
}

$data['total'] = $total;
```

<a name="usage-ec-setexpresscheckout"></a>
* **SetExpressCheckout**

    ```
    $response = $provider->setExpressCheckout($data);
    
    // Use the following line when creating recurring payment profiles (subscriptions)
    $response = $provider->setExpressCheckout($data, true);
    
     // This will redirect user to PayPal
    return redirect($response['paypal_link']);
    ```

<a name="usage-ec-getexpresscheckoutdetails"></a>
* **GetExpressCheckoutDetails**

    ```
    $response = $provider->getExpressCheckoutDetails($token);
    ```
    
<a name="usage-ec-doexpresscheckoutpayment"></a>
* **DoExpressCheckoutPayment** 

    ```
    // Note that 'token', 'PayerID' are values returned by PayPal when it redirects to success page after successful verification of user's PayPal info.
    $response = $provider->doExpressCheckoutPayment($data, $token, $PayerID);
    ```

<a name="usage-ec-refundtransaction"></a>
* **RefundTransaction**

    ```
    $response = $provider->refundTransaction($transactionid);
    ```

<a name="usage-ec-createbillingagreement"></a>    
* **CreateBillingAgreement**

    ```
    // The $token is the value returned from SetExpressCheckout API call
    $response = $provider->createBillingAgreement($token);
    ```    

<a name="usage-ec-createrecurringprofile"></a>
* **CreateRecurringPaymentsProfile**

    ```
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

    ```
    $response = $provider->getRecurringPaymentsProfileDetails($profileid);
    ```    

<a name="usage-ec-updaterecurringprofile"></a>
* **UpdateRecurringPaymentsProfile**

    ```
    $response = $provider->updateRecurringPaymentsProfile($data, $profileid);
    ```    

<a name="usage-ec-managerecurringprofile"></a>
* **ManageRecurringPaymentsProfileStatus**

    ```
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

```
PayPal::setProvider('adaptive_payments');
```

<a name="usage-adaptive-pay"></a>
* **Pay**

```

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

```
$redirect_url = $provider->getRedirectUrl('approved', $response['payKey']);

return redirect($redirect_url);
```

<a name="paypalipn"></a>
## Handling PayPal IPN
You can also handle Instant Payment Notifications from PayPal.
Suppose you have set IPN URL to **http://example.com/ipn/notify/** in PayPal. To handle IPN you should do the following:

* First add the **ipn/notify** tp your routes file:

    ```
    Route::post('ipn/notify','PayPalController@postNotify'); // Change it accordingly in your application
    ```
          
* Open **App\Http\Middleware\VerifyCsrfToken.php** and add your IPN route to **$excluded** routes variable.

    ```
    'ipn/notify'
    ```

* Then in the controller where you are handling IPN, do the following:

    ```
    // Put this above controller definition
    use Srmklive\PayPal\Traits\IPNResponse As PayPalIPN;
    
    // Then add the following before function declaration
    use PayPalIPN;
    ```
    
* The above step saves the PayPal IPN response as **ipn** in session. Following is the code you can change to your own requirements for handling IPN:    
    
    ```
    /**
     * Retrieve IPN Response From PayPal
     *
     * @param \Illuminate\Http\Request $request
     */
    public function postNotify(Request $request)
    {
        $post = [];
        $request_params = $request->all();

        foreach ($request_params as $key=>$value)
            $post[$key] = $value;

        $post['cmd'] = '_notify-validate';

        $response = $this->verifyIPN($post);

        session([
            'ipn' => $response
        ]);
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
 
