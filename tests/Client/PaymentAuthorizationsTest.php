<?php

namespace Srmklive\PayPal\Tests\Client;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class PaymentAuthorizationsTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_show_details_for_an_authorized_payment()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "0VF52814937998046",
  "status": "AUTHORIZED",
  "amount": {
    "total": "10.99",
    "currency": "USD"
  },
  "invoice_id": "INVOICE-123",
  "seller_protection": {
    "status": "ELIGIBLE",
    "dispute_categories": [
      "ITEM_NOT_RECEIVED",
      "UNAUTHORIZED_TRANSACTION"
    ]
  },
  "expiration_time": "2017-10-10T23:23:45Z",
  "create_time": "2017-09-11T23:23:45Z",
  "update_time": "2017-09-11T23:23:45Z",
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api.paypal.com/v2/payments/authorizations/0VF52814937998046"
    },
    {
      "rel": "capture",
      "method": "POST",
      "href": "https://api.paypal.com/v2/payments/authorizations/0VF52814937998046/capture"
    },
    {
      "rel": "void",
      "method": "POST",
      "href": "https://api.paypal.com/v2/payments/authorizations/0VF52814937998046/void"
    },
    {
      "rel": "reauthorize",
      "method": "POST",
      "href": "https://api.paypal.com/v2/payments/authorizations/0VF52814937998046/reauthorize"
    }
  ]
}', true);

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v2/payments/authorizations/0VF52814937998046';
        $expectedParams = [
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US',
                'Authorization' => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'get');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->get($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_capture_an_authorized_payment()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "2GG279541U471931P",
  "status": "COMPLETED",
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api.paypal.com/v2/payments/captures/2GG279541U471931P"
    },
    {
      "rel": "refund",
      "method": "POST",
      "href": "https://api.paypal.com/v2/payments/captures/2GG279541U471931P/refund"
    },
    {
      "rel": "up",
      "method": "GET",
      "href": "https://api.paypal.com/v2/payments/authorizations/0VF52814937998046"
    }
  ]
}', true);

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v2/payments/authorizations/0VF52814937998046/capture';
        $expectedParams = [
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US',
                'Authorization' => 'Bearer some-token',
            ],
            'json' => \GuzzleHttp\json_decode('{
  "amount": {
    "value": "10.99",
    "currency_code": "USD"
  },
  "invoice_id": "INVOICE-123",
  "note_to_payer": "Payment is due",
  "final_capture": true
}', true),
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_reauthorize_an_authorized_payment()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "8AA831015G517922L",
  "status": "CREATED",
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api.paypal.com/v2/payments/authorizations/8AA831015G517922L"
    },
    {
      "rel": "capture",
      "method": "POST",
      "href": "https://api.paypal.com/v2/payments/authorizations/8AA831015G517922L/capture"
    },
    {
      "rel": "void",
      "method": "POST",
      "href": "https://api.paypal.com/v2/payments/authorizations/8AA831015G517922L/void"
    },
    {
      "rel": "reauthorize",
      "method": "POST",
      "href": "https://api.paypal.com/v2/payments/authorizations/8AA831015G517922L/reauthorize"
    }
  ]
}', true);

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v2/payments/authorizations/0VF52814937998046/reauthorize';
        $expectedParams = [
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US',
                'Authorization' => 'Bearer some-token',
            ],
            'json' => \GuzzleHttp\json_decode('{
  "amount": {
    "value": "10.99",
    "currency_code": "USD"
  }
}', true),
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_void_an_authorized_payment()
    {
        $expectedResponse = '';

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v2/payments/authorizations/0VF52814937998046/void';
        $expectedParams = [
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US',
                'Authorization' => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
