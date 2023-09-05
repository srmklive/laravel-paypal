
Usage
=====


Initialization
--------------

.. code-block:: console

    // Import the class namespaces first, before using it directly
    use Srmklive\PayPal\Services\PayPal as PayPalClient;

    $provider = new PayPalClient;

    // Through facade. No need to import namespaces
    $provider = \PayPal::setProvider();


Get Access Token
----------------

After setting the PayPal API configuration, you need to get access token before performing any API calls

.. code-block:: console

    $provider->getAccessToken();


Override Configuration
----------------------

You can override PayPal API configuration by calling setApiCredentials method:

.. code-block:: console

    $config = [
        'mode'    => 'live',
        'live' => [
            'client_id'         => 'some-client-id',
            'client_secret'     => 'some-client-secret',
            'app_id'            => 'APP-80W284485P519543T',
        ],

        'payment_action' => 'Sale',
        'currency'       => 'USD',
        'notify_url'     => 'https://your-app.com/paypal/notify',
        'locale'         => 'en_US',
        'validate_ssl'   => true,
    ];

    $provider->setApiCredentials($config);
    


Change Currency
---------------

By default the currency used is **USD**. You can either add the `PAYPAL_CURRENCY` environment variable in your **.env** file or you may call `setCurrency` method to set a different currency before calling any respective API methods:

.. code-block:: console

    $provider->setCurrency('EUR');
