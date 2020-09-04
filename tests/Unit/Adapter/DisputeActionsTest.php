<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

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

        $expectedMethod = 'acceptDisputeClaim';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('PP-D-27803', 'Full refund to the customer.'));
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

        $expectedMethod = 'acceptDisputeOfferResolution';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('PP-000-000-651-454', 'I am ok with the refund offered.'));
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

        $expectedMethod = 'acknowledgeItemReturned';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('PP-000-000-651-454', 'I have received the item back.', 'ITEM_RECEIVED'));
    }
}
