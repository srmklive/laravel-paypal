
Helper Methods
==============

This package contains a lot of helper methods to make setting up API calls a lot easier. Here are all the available helper methods for performing API calls.


Subscription: Create Recurring Subscription (Daily)
---------------------------------------------------

.. code-block:: console

    $response = $provider->addProduct('Demo Product', 'Demo Product', 'SERVICE', 'SOFTWARE')
    ->addPlanTrialPricing('DAY', 7)
    ->addDailyPlan('Demo Plan', 'Demo Plan', 1.50)
    ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
    ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');


Subscription: Create Recurring Subscription (Weekly)
----------------------------------------------------

.. code-block:: console

    $response = $provider->addProduct('Demo Product', 'Demo Product', 'SERVICE', 'SOFTWARE')
    ->addPlanTrialPricing('DAY', 7)
    ->addWeeklyPlan('Demo Plan', 'Demo Plan', 30)
    ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
    ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');


Subscription: Create Recurring Subscription (Monthly)
-----------------------------------------------------

.. code-block:: console

    $response = $provider->addProduct('Demo Product', 'Demo Product', 'SERVICE', 'SOFTWARE')
    ->addPlanTrialPricing('DAY', 7)
    ->addMonthlyPlan('Demo Plan', 'Demo Plan', 100)
    ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
    ->setupSubscription('John Doe', 'john@example.com', '2021-12-10'); 


Subscription: Create Recurring Subscription (Annual)
----------------------------------------------------

.. code-block:: console

    $response = $provider->addProduct('Demo Product', 'Demo Product', 'SERVICE', 'SOFTWARE')
    ->addPlanTrialPricing('DAY', 7)
    ->addAnnualPlan('Demo Plan', 'Demo Plan', 600)
    ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
    ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');


Subscription: Create Recurring Subscription (Custom Intervals)
--------------------------------------------------------------

.. code-block:: console

    $response = $provider->addProduct('Demo Product', 'Demo Product', 'SERVICE', 'SOFTWARE')
    ->addCustomPlan('Demo Plan', 'Demo Plan', 150, 'MONTH', 3)
    ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
    ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');


Subscription: Create Subscription with Existing Product & Billing Plan
----------------------------------------------------------------------

.. code-block:: console

    $response = $provider->addProductById('PROD-XYAB12ABSB7868434')
    ->addBillingPlanById('P-5ML4271244454362WXNWU5NQ')
    ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
    ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');   


Subscription: Create Subscription with Setup Fee
------------------------------------------------

.. code-block:: console

    $response = $provider->addSetupFee(9.99)
    ->addProductById('PROD-XYAB12ABSB7868434')
    ->addBillingPlanById('P-5ML4271244454362WXNWU5NQ')
    ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
    ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');  


Subscription: Create Subscription with Shipping Address
-------------------------------------------------------

.. code-block:: console

    $response = $provider->addShippingAddress('John Doe', 'House no. 123', 'Street 456', 'Test Area', 'Test Area', 10001, 'US')
    ->addProductById('PROD-XYAB12ABSB7868434')
    ->addBillingPlanById('P-5ML4271244454362WXNWU5NQ')
    ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
    ->setupSubscription('John Doe', 'john@example.com', '2021-12-10'); 


Subscription: Create Subscription with Payment Failure Threshold
----------------------------------------------------------------

.. code-block:: console

    $response = $provider->addPaymentFailureThreshold(5)
    ->addProductById('PROD-XYAB12ABSB7868434')
    ->addBillingPlanById('P-5ML4271244454362WXNWU5NQ')
    ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
    ->setupSubscription('John Doe', 'john@example.com', '2021-12-10');    


Subscription: Create Subscription with Taxes
--------------------------------------------

.. code-block:: console

    $response = $provider->addTaxes(5)
    ->addProductById('PROD-XYAB12ABSB7868434')
    ->addBillingPlanById('P-5ML4271244454362WXNWU5NQ')
    ->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
    ->setupSubscription('John Doe', 'john@example.com', '2021-12-10'); 


Billing Plans: Update Pricing Schemes
-------------------------------------

.. code-block:: console

    $response = $this->client->addBillingPlanById('P-5ML4271244454362WXNWU5NQ')
    ->addPricingScheme('DAY', 7, 0, true)
    ->addPricingScheme('MONTH', 1, 100)
    ->processBillingPlanPricingUpdates();   