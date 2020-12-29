<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class DisputesTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_list_disputes()
    {
        $expectedResponse = $this->mockListDisputesResponse();

        $expectedMethod = 'listDisputes';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_partially_update_a_dispute()
    {
        $expectedResponse = '';

        $expectedParams = $this->updateDisputeParams();

        $expectedMethod = 'updateDispute';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, 'PP-D-27803'));
    }

    /** @test */
    public function it_can_get_details_for_a_dispute()
    {
        $expectedResponse = $this->mockGetDisputesResponse();

        $expectedMethod = 'showDisputeDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('PP-D-4012'));
    }
}
