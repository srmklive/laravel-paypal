<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class ReferencedPayoutsTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_create_referenced_batch_payout()
    {
        $expectedResponse = $this->mockCreateReferencedBatchPayoutResponse();

        $expectedParams = $this->mockCreateReferencedBatchPayoutParams();

        $expectedMethod = 'createReferencedBatchPayout';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, 'some-request-id', 'some-attribution-id'));
    }

    /** @test */
    public function it_can_list_items_referenced_in_batch_payout()
    {
        $expectedResponse = $this->mockShowReferencedBatchPayoutResponse();

        $expectedParams = 'KHbwO28lWlXwi2IlToJ2IYNG4juFv6kpbFx4J9oQ5Hb24RSp96Dk5FudVHd6v4E=';

        $expectedMethod = 'listItemsReferencedInBatchPayout';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }

    /** @test */
    public function it_can_create_referenced_batch_payout_item()
    {
        $expectedResponse = $this->mockCreateReferencedBatchPayoutItemResponse();

        $expectedParams = $this->mockCreateReferencedBatchPayoutItemParams();

        $expectedMethod = 'createReferencedBatchPayoutItem';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, 'some-request-id', 'some-attribution-id'));
    }

    /** @test */
    public function it_can_show_referenced_payout_item_details()
    {
        $expectedResponse = $this->mockShowReferencedBatchPayoutItemResponse();

        $expectedParams = 'CDZEC5MJ8R5HY';

        $expectedMethod = 'showReferencedPayoutItemDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, 'some-attribution-id'));
    }
}
