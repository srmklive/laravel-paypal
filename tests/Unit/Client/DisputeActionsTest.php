<?php

namespace Srmklive\PayPal\Tests\Unit\Client;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class DisputeActionsTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_accept_dispute_claim()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api.sandbox.paypal.com/v1/customer/disputes/PP-D-27803"
    }
  ]
}', true);

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v1/customer/disputes/PP-D-27803/accept-claim';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => \GuzzleHttp\json_decode('{
  "note": "Full refund to the customer.",
  "accept_claim_type": "REFUND"
}', true),
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_accept_dispute_offer_resolution()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api.sandbox.paypal.com/v1/customer/disputes/PP-000-000-651-454"
    }
  ]
}', true);

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v1/customer/disputes/PP-000-000-651-454/accept-offer';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => \GuzzleHttp\json_decode('{
  "note": "I am ok with the refund offered."
}', true),
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_acknowledge_item_is_returned_for_raised_dispute()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api.sandbox.paypal.com/v1/customer/disputes/PP-000-000-651-454"
    }
  ]
}', true);

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v1/customer/disputes/PP-000-000-651-454/acknowledge-return-item';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => \GuzzleHttp\json_decode('{
  "note": "I have received the item back.",
  "acknowledgement_type": "ITEM_RECEIVED"
}', true),
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
