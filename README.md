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
- [Handling PayPal IPN](#paypalipn)
- [Support](#support)

<a name="introduction"></a>
## Introduction

Laravel plugin For Processing Payments Through Paypal. Using this plugin you can process or refund payments and handle IPN (Instant Payment Notification) from PayPal in your Laravel application.

**Currently only PayPal Express Checkout & Adaptive Payments API Is Supported.**


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
'PayPal' => 'Srmklive\PayPal\Providers\Facades\PayPal' // Laravel 5
```
```
'PayPal' => Srmklive\PayPal\Providers\Facades\PayPal::class // Laravel 5.1 or greater
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

* Set Providers

```
PayPal::setProvider('express_checkout');    // To use PayPal Express Checkout API (Used by default)
PayPal::setProvider('adaptive_payments');   // To use PayPal Adaptive Payments API
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
    $response = PayPal::getProvider()->setExpressCheckout($data);
    
    // Use the following line when creating recurring payment profiles (subscriptions)
    $response = PayPal::getProvider()->setExpressCheckout($data, true);
    
     // This will redirect user to PayPal
    return redirect($response['paypal_link']);
    ```

<a name="usage-ec-getexpresscheckoutdetails"></a>
* **GetExpressCheckoutDetails**

    ```
    $response = PayPal::getProvider()->getExpressCheckoutDetails($token);
    ```
    
<a name="usage-ec-doexpresscheckoutpayment"></a>
* **DoExpressCheckoutPayment** 

    ```
    // Note that 'token', 'PayerID' are values returned by PayPal when it redirects to success page after successful verification of user's PayPal info.
    $response = PayPal::getProvider()->doExpressCheckoutPayment($data, $token, $PayerID);
    ```

<a name="usage-ec-refundtransaction"></a>
* **RefundTransaction**

    ```
    $response = PayPal::getProvider()->refundTransaction($transactionid);
    ```

<a name="usage-ec-createbillingagreement"></a>    
* **CreateBillingAgreement**

    ```
    // The $token is the value returned from SetExpressCheckout API call
    $response = PayPal::getProvider()->createBillingAgreement($token);
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
    $response = PayPal::getProvider()->createRecurringPaymentsProfile($data, $token);
    ```    

<a name="usage-ec-getrecurringprofiledetails"></a>
* **GetRecurringPaymentsProfileDetails**

    ```
    $response = PayPal::getProvider()->getRecurringPaymentsProfileDetails($profileid);
    ```    

<a name="usage-ec-updaterecurringprofile"></a>
* **UpdateRecurringPaymentsProfile**

    ```
    $response = PayPal::getProvider()->updateRecurringPaymentsProfile($data, $profileid);
    ```    

<a name="usage-ec-managerecurringprofile"></a>
* **ManageRecurringPaymentsProfileStatus**

    ```
    // Cancel recurring payment profile
    $response = PayPal::getProvider()->cancelRecurringPaymentsProfile($profileid);
    
    // Suspend recurring payment profile
    $response = PayPal::getProvider()->suspendRecurringPaymentsProfile($profileid);
    
    // Reactivate recurring payment profile
    $response = PayPal::getProvider()->reactivateRecurringPaymentsProfile($profileid);    
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
 
