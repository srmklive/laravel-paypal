# Laravel PayPal

- [Introduction](#introduction)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Handling PayPal IPN](#paypalipn)
- [Support](#support)

<a name="introduction"></a>
## Introduction

Laravel plugin For Processing Payments Through Paypal. Using this plugin you can process or refund payments and handle IPN (Instant Payment Notification) from PayPal in your Laravel application.

**Currently only PayPal Express Checkout Is Supported.**


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


* SetExpressCheckout

    ```
    $response = PayPal::getProvider()->setExpressCheckout($data);
    
    // Use the following line when creating recurring payment profiles (subscriptions)
    $response = PayPal::getProvider()->setExpressCheckout($data, true);
    
     // This will redirect user to PayPal
    return redirect($response['paypal_link']);
    ```

* GetExpressCheckoutDetails

    ```
    $response = PayPal::getProvider()->getExpressCheckoutDetails($token);
    ```

* DoExpressCheckoutPayment 

    ```
    // Note that 'token', 'PayerID' are values returned by PayPal when it redirects to success page after successful verification of user's PayPal info.
    $response = PayPal::getProvider()->doExpressCheckoutPayment($data, $token, $PayerID);
    ```

* RefundTransaction

    ```
    $response = PayPal::getProvider()->refundTransaction($transactionid);
    ```
    
* CreateBillingAgreement

    ```
    // The $token is the value returned from SetExpressCheckout API call
    $response = PayPal::getProvider()->createBillingAgreement($token);
    ```    

* CreateRecurringPaymentsProfile

    ```
    // The $token is the value returned from SetExpressCheckout API call
    $response = PayPal::getProvider()->createRecurringPaymentsProfile($data, $token);
    ```    


* GetRecurringPaymentsProfileDetails

    ```
    $response = PayPal::getProvider()->getRecurringPaymentsProfileDetails($profileid);
    ```    

* UpdateRecurringPaymentsProfile

    ```
    $response = PayPal::getProvider()->updateRecurringPaymentsProfile($data, $profileid);
    ```    

* ManageRecurringPaymentsProfileStatus

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
 
