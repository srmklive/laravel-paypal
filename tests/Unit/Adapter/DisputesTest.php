<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class DisputesTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_list_disputes()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "items": [
    {
      "dispute_id": "PP-000-003-648-191",
      "create_time": "2017-01-24T10:41:35.000Z",
      "update_time": "2017-01-24T11:40:32.000Z",
      "status": "WAITING_FOR_SELLER_RESPONSE",
      "reason": "MERCHANDISE_OR_SERVICE_NOT_RECEIVED",
      "dispute_state": "REQUIRED_OTHER_PARTY_ACTION",
      "dispute_amount": {
        "currency_code": "USD",
        "value": "50.00"
      },
      "links": [
        {
          "href": "https://api.sandbox.paypal.com/v1/customer/disputes/PP-000-003-648-191",
          "rel": "self",
          "method": "GET"
        }
      ]
    },
    {
      "dispute_id": "PP-000-003-648-175",
      "create_time": "2017-01-24T10:37:23.000Z",
      "update_time": "2017-01-24T11:32:32.000Z",
      "status": "UNDER_REVIEW",
      "reason": "UNAUTHORISED",
      "dispute_amount": {
        "currency_code": "USD",
        "value": "20.00"
      },
      "links": [
        {
          "href": "https://api.sandbox.paypal.com/v1/customer/disputes/PP-000-003-648-175",
          "rel": "self",
          "method": "GET"
        }
      ]
    }
  ],
  "links": [
    {
      "href": "https://api.sandbox.paypal.com/v1/customer/disputes",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.sandbox.paypal.com/v1/customer/disputes",
      "rel": "first",
      "method": "GET"
    }
  ]
}', true);

        $expectedMethod = 'listDisputes';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_partially_update_a_dispute()
    {
        $expectedResponse = '';

        $expectedParams = \GuzzleHttp\json_decode('[
  {
    "op": "add",
    "path": "/partner_actions/-",
    "value": {
      "id": "AMX-22345",
      "name": "ACCEPT_DISPUTE",
      "create_time": "2018-01-12T10:41:35.000Z",
      "status": "PENDING"
    }
  }
]', true);

        $expectedMethod = 'updateDispute';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, 'PP-D-27803'));
    }

    /** @test */
    public function it_can_get_details_for_a_dispute()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "dispute_id": "PP-D-4012",
  "create_time": "2019-04-11T04:18:00.000Z",
  "update_time": "2019-04-21T04:19:08.000Z",
  "disputed_transactions": [
    {
      "seller_transaction_id": "3BC38643YC807283D",
      "create_time": "2019-04-11T04:16:58.000Z",
      "transaction_status": "REVERSED",
      "gross_amount": {
        "currency_code": "USD",
        "value": "192.00"
      },
      "buyer": {
        "name": "Lupe Justin"
      },
      "seller": {
        "email": "merchant@example.com",
        "merchant_id": "5U29WL78XSAEL",
        "name": "Lesley Paul"
      }
    }
  ],
  "reason": "MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED",
  "status": "RESOLVED",
  "dispute_amount": {
    "currency_code": "USD",
    "value": "96.00"
  },
  "dispute_outcome": {
    "outcome_code": "RESOLVED_BUYER_FAVOUR",
    "amount_refunded": {
      "currency_code": "USD",
      "value": "96.00"
    }
  },
  "dispute_life_cycle_stage": "CHARGEBACK",
  "dispute_channel": "INTERNAL",
  "messages": [
    {
      "posted_by": "BUYER",
      "time_posted": "2019-04-11T04:18:04.000Z",
      "content": "SNAD case created through automation"
    }
  ],
  "extensions": {
    "merchandize_dispute_properties": {
      "issue_type": "SERVICE",
      "service_details": {
        "sub_reasons": [
          "INCOMPLETE"
        ],
        "purchase_url": "https://ebay.in"
      }
    }
  },
  "offer": {
    "buyer_requested_amount": {
      "currency_code": "USD",
      "value": "96.00"
    }
  },
  "links": [
    {
      "href": "https://api.sandbox.paypal.com/v1/customer/disputes/PP-D-4012",
      "rel": "self",
      "method": "GET"
    }
  ]
}', true);

        $expectedMethod = 'showDisputeDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('PP-D-4012'));
    }
}
