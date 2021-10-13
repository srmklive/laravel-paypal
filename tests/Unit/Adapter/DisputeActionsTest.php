<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class DisputeActionsTest extends TestCase
{
    use MockClientClasses;
    use MockResponsePayloads;

    /** @test */
    public function it_can_accept_dispute_claim()
    {
        $expectedResponse = $this->mockAcceptDisputesClaimResponse();

        $expectedMethod = 'acceptDisputeClaim';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('PP-D-27803', 'Full refund to the customer.'));
    }

    /** @test */
    public function it_can_accept_dispute_offer_resolution()
    {
        $expectedResponse = $this->mockAcceptDisputesOfferResolutionResponse();

        $expectedMethod = 'acceptDisputeOfferResolution';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('PP-000-000-651-454', 'I am ok with the refund offered.'));
    }

    /** @test */
    public function it_can_acknowledge_item_is_returned_for_raised_dispute()
    {
        $expectedResponse = $this->mockAcknowledgeItemReturnedResponse();

        $expectedMethod = 'acknowledgeItemReturned';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('PP-000-000-651-454', 'I have received the item back.', 'ITEM_RECEIVED'));
    }
}
