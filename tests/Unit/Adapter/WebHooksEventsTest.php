<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class WebHooksEventsTest extends TestCase
{
    use MockClientClasses;
    use MockResponsePayloads;

    /** @test */
    public function it_can_list_web_hooks_event_types()
    {
        $expectedResponse = $this->mockListWebHookEventsTypesResponse();

        $expectedMethod = 'listEventTypes';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_list_web_hooks_events()
    {
        $expectedResponse = $this->mockWebHookEventsListResponse();

        $expectedMethod = 'listEvents';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_show_details_for_a_web_hooks_event()
    {
        $expectedResponse = $this->mockGetWebHookEventResponse();

        $expectedMethod = 'showEventDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('8PT597110X687430LKGECATA'));
    }

    /** @test */
    public function it_can_resend_notification_for_a_web_hooks_event()
    {
        $expectedResponse = $this->mockResendWebHookEventNotificationResponse();

        $expectedParams = ['12334456'];

        $expectedMethod = 'resendEventNotification';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('8PT597110X687430LKGECATA', $expectedParams));
    }
}
