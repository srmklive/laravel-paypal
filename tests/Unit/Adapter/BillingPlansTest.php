<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class BillingPlansTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_create_a_billing_plan()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
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

        $expectedParams = \GuzzleHttp\json_decode('{
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
}', true);

        $expectedMethod = 'createPlan';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }

    /** @test */
    public function it_can_list_billing_plans()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "total_items": 12,
  "total_pages": 6,
  "plans": [
    {
      "id": "P-5ML4271244454362WXNWU5NQ",
      "product_id": "PROD-XXCD1234QWER65782",
      "status": "ACTIVE",
      "name": "Zoho Marketing Campaign  Plan",
      "description": "Zoho Marketing Campaign Plan",
      "create_time": "2018-12-10T21:20:49Z",
      "links": [
        {
          "href": "https://api.paypal.com/v1/billing/plans/P-5ML4271244454362WXNWU5NQ",
          "rel": "self",
          "method": "GET"
        }
      ]
    },
    {
      "id": "P-6LL4271454454362WXNWU5NQ",
      "product_id": "PROD-XXCD1234QWER65782",
      "status": "ACTIVE",
      "name": "Zoho Marketing Campaign Basic Plan",
      "description": "Zoho Marketing Campaign Plan",
      "create_time": "2019-01-10T21:20:49Z",
      "links": [
        {
          "href": "https://api.paypal.com/v1/billing/plans/P-6LL4271454454362WXNWU5NQ",
          "rel": "self",
          "method": "GET"
        }
      ]
    }
  ],
  "links": [
    {
      "href": "https://api.paypal.com/v1/billing/plans?product_id=PROD-XXCD1234QWER65782&page_size=2&page=1",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/billing/plans?product_id=PROD-XXCD1234QWER65782&page_size=2&page=1",
      "rel": "first",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/billing/plans?product_id=PROD-XXCD1234QWER65782&page_size=2&page=2",
      "rel": "next",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/billing/plans?product_id=PROD-XXCD1234QWER65782&page_size=2&page=6",
      "rel": "last",
      "method": "GET"
    }
  ]
}', true);

        $expectedMethod = 'listPlans';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}(1, 2, true));
    }

    /** @test */
    public function it_can_update_a_billing_plan()
    {
        $expectedResponse = '';

        $expectedParams = \GuzzleHttp\json_decode('[
  {
    "op": "replace",
    "path": "/payment_preferences/payment_failure_threshold",
    "value": 7
  }
]', true);

        $expectedMethod = 'updatePlan';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('P-7GL4271244454362WXNWU5NQ', $expectedParams));
    }

    /** @test */
    public function it_can_show_details_for_a_billing_plan()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "P-5ML4271244454362WXNWU5NQ",
  "product_id": "PROD-XXCD1234QWER65782",
  "name": "Basic Plan",
  "description": "Basic Plan",
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
          "currency_code": "USD",
          "value": "3"
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
          "value": "10",
          "currency_code": "USD"
        },
        "status": "ACTIVE",
        "version": 1,
        "create_time": "2020-05-27T12:13:51Z",
        "update_time": "2020-05-27T12:13:51Z"
      }
    }
  ],
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

        $expectedMethod = 'showPlanDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('P-7GL4271244454362WXNWU5NQ'));
    }

    /** @test */
    public function it_can_activate_a_billing_plan()
    {
        $expectedResponse = '';

        $expectedMethod = 'activatePlan';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('P-7GL4271244454362WXNWU5NQ'));
    }

    /** @test */
    public function it_can_deactivate_a_billing_plan()
    {
        $expectedResponse = '';

        $expectedMethod = 'deactivatePlan';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('P-7GL4271244454362WXNWU5NQ'));
    }

    /** @test */
    public function it_can_update_pricing_for_a_billing_plan()
    {
        $expectedResponse = '';

        $expectedParams = \GuzzleHttp\json_decode('{
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
}', true);

        $expectedMethod = 'updatePlanPricing';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('P-2UF78835G6983425GLSM44MA', $expectedParams));
    }
}
