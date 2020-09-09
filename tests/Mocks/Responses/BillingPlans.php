<?php

namespace Srmklive\PayPal\Tests\Mocks\Responses;

trait BillingPlans
{
    private function mockCreatePlansResponse()
    {
        return \GuzzleHttp\json_decode('{
  "id": "P-5ML4271244454362WXNWU5NQ",
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
        },
        "version": 1,
        "create_time": "2020-05-27T12:13:51Z",
        "update_time": "2020-05-27T12:13:51Z"
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
          "currency_code": "USD",
          "value": "6"
        },
        "version": 1,
        "create_time": "2020-05-27T12:13:51Z",
        "update_time": "2020-05-27T12:13:51Z"
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
          "currency_code": "USD",
          "value": "10"
        },
        "version": 1,
        "create_time": "2020-05-27T12:13:51Z",
        "update_time": "2020-05-27T12:13:51Z"
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
  },
  "create_time": "2020-05-27T12:13:51Z",
  "update_time": "2020-05-27T12:13:51Z",
  "links": [
    {
      "href": "https://api.paypal.com/v1/billing/plans/P-5ML4271244454362WXNWU5NQ",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/billing/plans/P-5ML4271244454362WXNWU5NQ",
      "rel": "edit",
      "method": "PATCH"
    },
    {
      "href": "https://api.paypal.com/v1/billing/plans/P-5ML4271244454362WXNWU5NQ/deactivate",
      "rel": "deactivate",
      "method": "POST"
    },
    {
      "href": "https://api.paypal.com/v1/billing/plans/P-5ML4271244454362WXNWU5NQ/update-pricing-schemes",
      "rel": "edit",
      "method": "POST"
    }
  ]
}', true);
    }
}
