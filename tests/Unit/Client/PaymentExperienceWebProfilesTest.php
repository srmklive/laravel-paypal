<?php

namespace Srmklive\PayPal\Tests\Unit\Client;

use GuzzleHttp\Utils;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class PaymentExperienceWebProfilesTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_list_web_experience_profiles()
    {
        $expectedResponse = $this->mockListWebProfilesResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/payment-experience/web-profiles';
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
    public function it_can_create_web_experience_profile()
    {
        $expectedResponse = $this->mockWebProfileResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/payment-experience/web-profiles';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
                'PayPal-Request-Id' => 'some-request-id',
            ],
            'json' => $this->mockCreateWebProfileParams(),
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_delete_web_experience_profile()
    {
        $expectedResponse = '';

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/payment-experience/web-profiles/XP-A88A-LYLW-8Y3X-E5ER';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'delete');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->delete($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_partially_update_web_experience_profile()
    {
        $expectedResponse = '';

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/payment-experience/web-profiles/XP-A88A-LYLW-8Y3X-E5ER';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => $this->partiallyUpdateWebProfileParams(),
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'patch');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->patch($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_fully_update_web_experience_profile()
    {
        $expectedResponse = '';

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/payment-experience/web-profiles/XP-A88A-LYLW-8Y3X-E5ER';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => $this->updateWebProfileParams(),
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'patch');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->patch($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_get_web_experience_profile_details()
    {
        $expectedResponse = $this->mockWebProfileResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/payment-experience/web-profiles/XP-A88A-LYLW-8Y3X-E5ER';
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
}
