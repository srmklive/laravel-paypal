<?php

namespace Srmklive\PayPal\Tests\Unit;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Utils;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class ClientTest extends TestCase
{
    use MockClientClasses;
    use MockResponsePayloads;

    /** @test */
    public function it_can_be_instantiated()
    {
        $client = new HttpClient();
        $this->assertInstanceOf(HttpClient::class, $client);
    }

    /** @test */
    public function it_can_get_access_token()
    {
        $expectedResponse = $this->mockAccessTokenResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/oauth2/token?grant_type=client_credentials';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
            ],
            'auth' => ['username', 'password'],
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams);

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
