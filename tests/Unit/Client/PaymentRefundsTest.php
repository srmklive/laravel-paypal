<?php

namespace Srmklive\PayPal\Tests\Unit\Client;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class PaymentRefundsTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_show_details_for_a_refund()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "1JU08902781691411",
  "amount": {
    "value": "10.99",
    "currency_code": "USD"
  },
  "status": "COMPLETED",
  "note_to_payer": "Defective product",
  "seller_payable_breakdown": {
    "gross_amount": {
      "value": "10.99",
      "currency_code": "USD"
    },
    "paypal_fee": {
      "value": "0",
      "currency_code": "USD"
    },
    "net_amount": {
      "value": "10.99",
      "currency_code": "USD"
    },
    "total_refunded_amount": {
      "value": "10.99",
      "currency_code": "USD"
    }
  },
  "invoice_id": "INVOICE-123",
  "create_time": "2018-09-11T23:24:19Z",
  "update_time": "2018-09-11T23:24:19Z",
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

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v2/payments/refunds/1JU08902781691411';
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
}
