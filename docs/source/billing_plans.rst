:orphan:

Billing Plans
=============

This package the following API endpoints for Billing Plans:

* Create Billing Plan.
* List Billing Plans.
* Show Billing Plan Details.
* Update Billing Plan.
* Activate Billing Plan.
* Deactivate Billing Plan.
* Update Billing Plan Pricing Scheme.


Create Billing Plan
-------------------

This implementation creates a billing plan that defines pricing and billing cycle details for subscriptions by implementing the following API:

https://developer.paypal.com/docs/api/subscriptions/v1/#plans_create

.. code-block:: console

    $data = json_decode("{
    "product_id": "PROD-XXCD1234QWER65782",
    "name": "Video Streaming Service Plan",
    "description": "Video Streaming Service basic plan",
    "status": "ACTIVE",
    "billing_cycles": [
        {
        "frequency": {
            "interval_unit": "MONTH",
            "interval_count": 1
        },
        "tenure_type": "TRIAL",
        "sequence": 1,
        "total_cycles": 2,
        "pricing_scheme": {
            "fixed_price": {
            "value": "3",
            "currency_code": "USD"
            }
        }
        },
        {
        "frequency": {
            "interval_unit": "MONTH",
            "interval_count": 1
        },
        "tenure_type": "TRIAL",
        "sequence": 2,
        "total_cycles": 3,
        "pricing_scheme": {
            "fixed_price": {
            "value": "6",
            "currency_code": "USD"
            }
        }
        },
        {
        "frequency": {
            "interval_unit": "MONTH",
            "interval_count": 1
        },
        "tenure_type": "REGULAR",
        "sequence": 3,
        "total_cycles": 12,
        "pricing_scheme": {
            "fixed_price": {
            "value": "10",
            "currency_code": "USD"
            }
        }
        }
    ],
    "payment_preferences": {
        "auto_bill_outstanding": true,
        "setup_fee": {
        "value": "10",
        "currency_code": "USD"
        },
        "setup_fee_failure_action": "CONTINUE",
        "payment_failure_threshold": 3
    },
    "taxes": {
        "percentage": "10",
        "inclusive": false
    }
    }", true);

    $plan = $provider->createPlan($data);


List Billing Plans
------------------

This implementation lists billing plans by implementing the following API:

https://developer.paypal.com/docs/api/subscriptions/v1/#plans_list

.. code-block:: console

    $plans = $provider->listPlans();

By default, the API returns a paginated response and only includes the first 20 results. However if you pass your own parameters, you can do writing the following:

.. code-block:: console

    $provider = $provider->setPageSize(30)->showTotals(true);
    $plans = $provider->setCurrentPage(1)->listPlans();

In the above snippet, we are returning the plans containing upto 30 items in each paginated response along with count details.


Show Billing Plan Details
-------------------------

This implementation shows details for a billing plan by implementing the following API:

https://developer.paypal.com/docs/api/subscriptions/v1/#plans_get

.. code-block:: console

    $plan_id = 'P-7GL4271244454362WXNWU5NQ';

    $plan = $provider->showPlanDetails($plan_id);


Update Billing Plan
-------------------

This implementation updates details for a billing plan by implementing the following API:

https://developer.paypal.com/docs/api/subscriptions/v1/#plans_patch

.. code-block:: console

    $data = json_decode("[
    {
        "op": "replace",
        "path": "/payment_preferences/payment_failure_threshold",
        "value": 7
    }
    ]", true);

    $plan_id = 'P-7GL4271244454362WXNWU5NQ';

    $plan = $provider->updatePlan($plan_id, $data);


Activate Billing Plan
---------------------

This implementation activates a billing plan by implementing the following API:

https://developer.paypal.com/docs/api/subscriptions/v1/#plans_activate

.. code-block:: console

    $plan_id = 'P-7GL4271244454362WXNWU5NQ';

    $plan = $provider->activatePlan($plan_id);


Dectivate Billing Plan
----------------------

This implementation deactivates a billing plan by implementing the following API:

https://developer.paypal.com/docs/api/subscriptions/v1/#plans_deactivate

.. code-block:: console

    $plan_id = 'P-7GL4271244454362WXNWU5NQ';

    $plan = $provider->deactivatePlan($plan_id);


Update Billing Plan Pricing Scheme
----------------------------------

This implementation updates pricing scheme for a billing plan by implementing the following API:

https://developer.paypal.com/docs/api/subscriptions/v1/#plans_update-pricing-schemes

.. code-block:: console

    $pricing = json_decode("{
    "pricing_schemes": [
        {
        "billing_cycle_sequence": 2,
        "pricing_scheme": {
            "fixed_price": {
            "value": "50",
            "currency_code": "USD"
            }
        }
        }
    ]
    }", true);

    $plan_id = 'P-7GL4271244454362WXNWU5NQ';

    $plan = $provider->updatePlanPricing($plan_id, $pricing);