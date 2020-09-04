<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class SubscriptionsTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_create_a_subscription()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "I-BW452GLLEP1G",
  "status": "APPROVAL_PENDING",
  "status_update_time": "2018-12-10T21:20:49Z",
  "plan_id": "P-5ML4271244454362WXNWU5NQ",
  "start_time": "2018-11-01T00:00:00Z",
  "quantity": "20",
  "shipping_amount": {
    "currency_code": "USD",
    "value": "10.00"
  },
  "subscriber": {
    "name": {
      "given_name": "John",
      "surname": "Doe"
    },
    "email_address": "customer@example.com",
    "payer_id": "2J6QB8YJQSJRJ",
    "shipping_address": {
      "name": {
        "full_name": "John Doe"
      },
      "address": {
        "address_line_1": "2211 N First Street",
        "address_line_2": "Building 17",
        "admin_area_2": "San Jose",
        "admin_area_1": "CA",
        "postal_code": "95131",
        "country_code": "US"
      }
    }
  },
  "create_time": "2018-12-10T21:20:49Z",
  "links": [
    {
      "href": "https://www.paypal.com/webapps/billing/subscriptions?ba_token=BA-2M539689T3856352J",
      "rel": "approve",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G",
      "rel": "edit",
      "method": "PATCH"
    },
    {
      "href": "https://api.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G",
      "rel": "self",
      "method": "GET"
    }
  ]
}', true);

        $expectedParams = \GuzzleHttp\json_decode('{
  "plan_id": "P-5ML4271244454362WXNWU5NQ",
  "start_time": "2018-11-01T00:00:00Z",
  "quantity": "20",
  "shipping_amount": {
    "currency_code": "USD",
    "value": "10.00"
  },
  "subscriber": {
    "name": {
      "given_name": "John",
      "surname": "Doe"
    },
    "email_address": "customer@example.com",
    "shipping_address": {
      "name": {
        "full_name": "John Doe"
      },
      "address": {
        "address_line_1": "2211 N First Street",
        "address_line_2": "Building 17",
        "admin_area_2": "San Jose",
        "admin_area_1": "CA",
        "postal_code": "95131",
        "country_code": "US"
      }
    }
  },
  "application_context": {
    "brand_name": "walmart",
    "locale": "en-US",
    "shipping_preference": "SET_PROVIDED_ADDRESS",
    "user_action": "SUBSCRIBE_NOW",
    "payment_method": {
      "payer_selected": "PAYPAL",
      "payee_preferred": "IMMEDIATE_PAYMENT_REQUIRED"
    },
    "return_url": "https://example.com/returnUrl",
    "cancel_url": "https://example.com/cancelUrl"
  }
}', true);

        $expectedMethod = 'createSubscription';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }

    /** @test */
    public function it_can_update_a_subscription()
    {
        $expectedResponse = '';

        $expectedParams = \GuzzleHttp\json_decode('[
  {
    "op": "replace",
    "path": "/billing_info/outstanding_balance",
    "value": {
      "currency_code": "USD",
      "value": "50.00"
    }
  }
]', true);

        $expectedMethod = 'updateSubscription';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('I-BW452GLLEP1G', $expectedParams));
    }

    /** @test */
    public function it_can_show_details_for_a_subscription()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "I-BW452GLLEP1G",
  "plan_id": "P-5ML4271244454362WXNWU5NQ",
  "start_time": "2019-04-10T07:00:00Z",
  "quantity": "20",
  "shipping_amount": {
    "currency_code": "USD",
    "value": "10.0"
  },
  "subscriber": {
    "shipping_address": {
      "name": {
        "full_name": "John Doe"
      },
      "address": {
        "address_line_1": "2211 N First Street",
        "address_line_2": "Building 17",
        "admin_area_2": "San Jose",
        "admin_area_1": "CA",
        "postal_code": "95131",
        "country_code": "US"
      }
    },
    "name": {
      "given_name": "John",
      "surname": "Doe"
    },
    "email_address": "customer@example.com",
    "payer_id": "2J6QB8YJQSJRJ"
  },
  "billing_info": {
    "outstanding_balance": {
      "currency_code": "USD",
      "value": "1.0"
    },
    "cycle_executions": [
      {
        "tenure_type": "TRIAL",
        "sequence": 1,
        "cycles_completed": 0,
        "cycles_remaining": 2,
        "total_cycles": 2
      },
      {
        "tenure_type": "TRIAL",
        "sequence": 2,
        "cycles_completed": 0,
        "cycles_remaining": 3,
        "total_cycles": 3
      },
      {
        "tenure_type": "REGULAR",
        "sequence": 3,
        "cycles_completed": 0,
        "cycles_remaining": 12,
        "total_cycles": 12
      }
    ],
    "last_payment": {
      "amount": {
        "currency_code": "USD",
        "value": "1.15"
      },
      "time": "2019-04-09T10:27:20Z"
    },
    "next_billing_time": "2019-04-10T10:00:00Z",
    "failed_payments_count": 0
  },
  "create_time": "2019-04-09T10:26:04Z",
  "update_time": "2019-04-09T10:27:27Z",
  "links": [
    {
      "href": "https://api.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/cancel",
      "rel": "cancel",
      "method": "POST"
    },
    {
      "href": "https://api.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G",
      "rel": "edit",
      "method": "PATCH"
    },
    {
      "href": "https://api.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/suspend",
      "rel": "suspend",
      "method": "POST"
    },
    {
      "href": "https://api.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/capture",
      "rel": "capture",
      "method": "POST"
    }
  ],
  "status": "ACTIVE",
  "status_update_time": "2019-04-09T10:27:27Z"
}', true);

        $expectedMethod = 'showSubscriptionDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('I-BW452GLLEP1G'));
    }

    /** @test */
    public function it_can_activate_a_subscription()
    {
        $expectedResponse = '';

        $expectedMethod = 'activateSubscription';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('I-BW452GLLEP1G', 'Reactivating the subscription'));
    }

    /** @test */
    public function it_can_cancel_a_subscription()
    {
        $expectedResponse = '';

        $expectedMethod = 'cancelSubscription';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('I-BW452GLLEP1G', 'Not satisfied with the service'));
    }

    /** @test */
    public function it_can_suspend_a_subscription()
    {
        $expectedResponse = '';

        $expectedMethod = 'suspendSubscription';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('I-BW452GLLEP1G', 'Item out of stock'));
    }

    /** @test */
    public function it_can_capture_payment_for_a_subscription()
    {
        $expectedResponse = '';

        $expectedMethod = 'captureSubscriptionPayment';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('I-BW452GLLEP1G', 'Charging as the balance reached the limit', 100));
    }

    /** @test */
    public function it_can_update_quantity_or_product_for_a_subscription()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "plan_id": "P-5ML4271244454362WXNWU5NQ",
  "effective_time": "2018-11-01T00:00:00Z",
  "shipping_amount": {
    "currency_code": "USD",
    "value": "10.00"
  },
  "shipping_address": {
    "name": {
      "full_name": "John Doe"
    },
    "address": {
      "address_line_1": "2211 N First Street",
      "address_line_2": "Building 17",
      "admin_area_2": "San Jose",
      "admin_area_1": "CA",
      "postal_code": "95131",
      "country_code": "US"
    }
  },
  "links": [
    {
      "href": "https://www.paypal.com/webapps/billing/subscriptions/update?ba_token=BA-2M539689T3856352J",
      "rel": "approve",
      "method": "GET"
    }
  ]
}', true);

        $expectedParams = \GuzzleHttp\json_decode('{
  "plan_id": "P-5ML4271244454362WXNWU5NQ",
  "shipping_amount": {
    "currency_code": "USD",
    "value": "10.00"
  },
  "shipping_address": {
    "name": {
      "full_name": "John Doe"
    },
    "address": {
      "address_line_1": "2211 N First Street",
      "address_line_2": "Building 17",
      "admin_area_2": "San Jose",
      "admin_area_1": "CA",
      "postal_code": "95131",
      "country_code": "US"
    }
  },
  "application_context": {
    "brand_name": "walmart",
    "locale": "en-US",
    "shipping_preference": "SET_PROVIDED_ADDRESS",
    "payment_method": {
      "payer_selected": "PAYPAL",
      "payee_preferred": "IMMEDIATE_PAYMENT_REQUIRED"
    },
    "return_url": "https://example.com/returnUrl",
    "cancel_url": "https://example.com/cancelUrl"
  }
}', true);

        $expectedMethod = 'reviseSubscription';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('I-BW452GLLEP1G', $expectedParams));
    }

    /** @test */
    public function it_can_list_transactions_for_a_subscription()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "transactions": [
    {
      "id": "TRFGHNJKOIIOJKL",
      "status": "COMPLETED",
      "payer_email": "customer@example.com",
      "payer_name": {
        "given_name": "John",
        "surname": "Doe"
      },
      "amount_with_breakdown": {
        "gross_amount": {
          "currency_code": "USD",
          "value": "10.00"
        },
        "fee_amount": {
          "currency_code": "USD",
          "value": "1.00"
        },
        "net_amount": {
          "currency_code": "USD",
          "value": "9.00"
        }
      },
      "time": "2018-03-16T07:40:20.940Z"
    },
    {
      "id": "VDFG45345FFGS",
      "status": "COMPLETED",
      "payer_email": "customer2@example.com",
      "payer_name": {
        "given_name": "Jhonny",
        "surname": "Cat"
      },
      "amount_with_breakdown": {
        "gross_amount": {
          "currency_code": "USD",
          "value": "15.00"
        },
        "fee_amount": {
          "currency_code": "USD",
          "value": "1.00"
        },
        "net_amount": {
          "currency_code": "USD",
          "value": "14.00"
        }
      },
      "time": "2018-08-21T07:50:20.940Z"
    }
  ],
  "links": [
    {
      "href": "https://api.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/transactions?start_time=2018-01-21T07:50:20.940Z&end_time=2018-08-21T07:50:20.940Z",
      "rel": "self",
      "method": "GET"
    }
  ]
}', true);

        $expectedMethod = 'listSubscriptionTransactions';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('I-BW452GLLEP1G', '2018-01-21T07:50:20.940Z', '2018-08-22T07:50:20.940Z'));
    }
}
