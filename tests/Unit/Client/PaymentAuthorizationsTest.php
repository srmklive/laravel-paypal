<?php

namespace Srmklive\PayPal\Tests\Unit\Client;

use GuzzleHttp\Utils;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class PaymentAuthorizationsTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_show_details_for_an_authorized_payment()
    {
        $expectedResponse = $this->mockGetAuthorizedPaymentDetailsResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v2/payments/authorizations/0VF52814937998046';
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
    public function it_can_capture_an_authorized_payment()
    {
        $expectedResponse = $this->mockCaptureAuthorizedPaymentResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v2/payments/authorizations/0VF52814937998046/capture';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => $this->mockCaptureAuthorizedPaymentParams(),
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_reauthorize_an_authorized_payment()
    {
        $expectedResponse = $this->mockReAuthorizeAuthorizedPaymentResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v2/payments/authorizations/0VF52814937998046/reauthorize';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => $this->mockReAuthorizeAuthorizedPaymentParams(),
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_void_an_authorized_payment()
    {
        $expectedResponse = '';

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v2/payments/authorizations/0VF52814937998046/void';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
