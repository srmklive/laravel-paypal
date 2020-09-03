<?php

namespace Srmklive\PayPal\Tests\Unit\Client;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class PaymentCapturesTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_show_details_for_a_captured_payment()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "2GG279541U471931P",
  "status": "COMPLETED",
  "status_details": {},
  "amount": {
    "total": "10.99",
    "currency": "USD"
  },
  "final_capture": true,
  "seller_protection": {
    "status": "ELIGIBLE",
    "dispute_categories": [
      "ITEM_NOT_RECEIVED",
      "UNAUTHORIZED_TRANSACTION"
    ]
  },
  "seller_receivable_breakdown": {
    "gross_amount": {
      "total": "10.99",
      "currency": "USD"
    },
    "paypal_fee": {
      "value": "0.33",
      "currency": "USD"
    },
    "net_amount": {
      "value": "10.66",
      "currency": "USD"
    }
  },
  "invoice_id": "INVOICE-123",
  "create_time": "2017-09-11T23:24:01Z",
  "update_time": "2017-09-11T23:24:01Z",
  "links": [
    {
      "href": "https://api.paypal.com/v2/payments/captures/2GG279541U471931P",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v2/payments/captures/2GG279541U471931P/refund",
      "rel": "refund",
      "method": "POST"
    },
    {
      "href": "https://api.paypal.com/v2/payments/authorizations/0VF52814937998046",
      "rel": "up",
      "method": "GET"
    }
  ]
}', true);

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v2/payments/captures/2GG279541U471931P';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'get');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->get($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_refund_a_captured_payment()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "1JU08902781691411",
  "status": "COMPLETED",
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api.paypal.com/v2/payments/refunds/1JU08902781691411"
    },
    {
      "rel": "up",
      "method": "GET",
      "href": "https://api.paypal.com/v2/payments/captures/2GG279541U471931P"
    }
  ]
}', true);

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v2/payments/captures/2GG279541U471931P/refund';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => \GuzzleHttp\json_decode('{
  "amount": {
    "value": "10.99",
    "currency_code": "USD"
  },
  "invoice_id": "INVOICE-123",
  "note_to_payer": "Defective product"
}', true),
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
