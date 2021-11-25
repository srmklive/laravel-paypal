<?php

namespace Srmklive\PayPal\Tests\Unit\Client;

use GuzzleHttp\Utils;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class WebHooksEventsTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_list_web_hooks_event_types()
    {
        $expectedResponse = $this->mockListWebHookEventsTypesResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/notifications/webhooks-event-types';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'get');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->get($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_list_web_hooks_events()
    {
        $expectedResponse = $this->mockWebHookEventsListResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/notifications/webhooks-events';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'get');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->get($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_show_details_for_a_web_hooks_event()
    {
        $expectedResponse = $this->mockGetWebHookEventResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/notifications/webhooks-events/8PT597110X687430LKGECATA';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'get');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->get($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_resend_notification_for_a_web_hooks_event()
    {
        $expectedResponse = $this->mockResendWebHookEventNotificationResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/notifications/webhooks-events/8PT597110X687430LKGECATA/resend';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => $this->mockResendWebHookEventNotificationParams(),
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'get');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->get($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
