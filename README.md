# Laravel PayPal

- [Introduction](#introduction)
- [Installation](#installation)
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
'Srmklive\PayPal\PayPalServiceProvider' // Laravel 5
```
```
Srmklive\PayPal\PayPalServiceProvider::class // Laravel 5.1
```

* Add the alias to your $aliases array in config/app.php file like: 

```
'PayPal' => 'Srmklive\PayPal\Facades\PayPal' // Laravel 5
```
```
'PayPal' => Srmklive\PayPal\Facades\PayPal::class // Laravel 5.1
```

* Run the following command to publish configuration:
```
php artisan vendor:publish
```
<a name="support"></a>
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
$response = PayPal::setExpressCheckout($data);
```
Now redirect to paypal using this:
```
return redirect($response['paypal_link']);
```

* GetExpressCheckoutDetails
```
$response = PayPal::getExpressCheckoutDetails($token);
```

* DoExpressCheckoutPayment 
```
$response = PayPal::doExpressCheckoutPayment($data, $token, $PayerID);

// Note that 'token', 'PayerID' are values returned by PayPal when it redirects to success page after successful verification of user's PayPal info.
```

* RefundTransaction
```
$response = PayPal::refundTransaction($transactionid);
```

<a name="paypalipn"></a>
## Handling PayPal IPN

Included in this package is controller **PayPalIPNController**. This demo controller includes code on handling Instant Payment Notifications from PayPal, and saves the IPN response in session as **paypal_ipn_response**. 
You can use this in your application like this:

* Open **App\Http\Middleware\VerifyCsrfToken.php** and add your IPN route to **$excluded** routes variable.
```
'notify'
```

* You can also extend it using your own controller like this: 

```
class IPNController extends PayPalIPNController
{
    public function postNotify(Request $request)
    {
        parent::postNotify($request);
              
        $response = Session::get('paypal_ipn_response');
        
        // Do your processing on IPN response                               
    }
}
```

<a name="support"></a>
## Support

This plugin only supports Laravel 5 & Laravel 5.1.
* In case of any issues, kindly create one on the [Issues](https://github.com/srmklive/laravel-paypal/issues) section.
* If you would like to contribute:
  * Fork this repository.
  * Implement your features.
  * Generate pull request.
 