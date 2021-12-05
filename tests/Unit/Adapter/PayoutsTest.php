<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class PayoutsTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_create_batch_payout()
    {
        $expectedResponse = $this->mockCreateBatchPayoutResponse();

        $expectedParams = $this->mockCreateBatchPayoutParams();

        $expectedMethod = 'createBatchPayout';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }

    /** @test */
    public function it_can_show_batch_payout_details()
    {
        $expectedResponse = $this->showBatchPayoutResponse();

        $expectedParams = 'FYXMPQTX4JC9N';

        $expectedMethod = 'showBatchPayoutDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }

    /** @test */
    public function it_can_show_batch_payout_item_details()
    {
        $expectedResponse = $this->showBatchPayoutItemResponse();

        $expectedParams = '8AELMXH8UB2P8';

        $expectedMethod = 'showPayoutItemDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }

    /** @test */
    public function it_can_cancel_unclaimed_batch_payout_item()
    {
        $expectedResponse = $this->mockCancelUnclaimedBatchItemResponse();

        $expectedParams = '8AELMXH8UB2P8';

        $expectedMethod = 'cancelUnclaimedPayoutItem';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }
}
