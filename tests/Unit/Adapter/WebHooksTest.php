<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class WebHooksTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_create_a_web_hook()
    {
        $expectedResponse = $this->mockCreateWebHookResponse();

        $expectedMethod = 'createWebHook';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}(
            'https://example.com/example_webhook',
            ['PAYMENT.AUTHORIZATION.CREATED', 'PAYMENT.AUTHORIZATION.VOIDED']
        ));
    }

    /** @test */
    public function it_can_list_web_hooks()
    {
        $expectedResponse = $this->mockListWebHookResponse();

        $expectedMethod = 'listWebHooks';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_delete_a_web_hook()
    {
        $expectedResponse = '';

        $expectedMethod = 'deleteWebHook';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('5GP028458E2496506'));
    }

    /** @test */
    public function it_can_update_a_web_hook()
    {
        $expectedResponse = $this->mockUpdateWebHookResponse();

        $expectedParams = $this->mockUpdateWebHookParams();

        $expectedMethod = 'updateWebHook';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('0EH40505U7160970P', $expectedParams));
    }

    /** @test */
    public function it_can_show_details_for_a_web_hook()
    {
        $expectedResponse = $this->mockGetWebHookResponse();

        $expectedMethod = 'showWebHookDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('0EH40505U7160970P'));
    }

    /** @test */
    public function it_can_list_web_hooks_events()
    {
        $expectedResponse = $this->mockListWebHookEventsResponse();

        $expectedMethod = 'listWebHookEvents';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('0EH40505U7160970P'));
    }
}
