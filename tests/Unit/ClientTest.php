<?php

namespace Srmklive\PayPal\Tests\Unit;

use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class ClientTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_be_instantiated()
    {
        $client = new HttpClient();
        $this->assertInstanceOf(HttpClient::class, $client);
    }

    /** @test */
    public function it_can_get_access_token()
    {
        $expectedResponse = [
            'scope'         => 'some_scope',
            'access_token'  => 'some_access_token',
            'token_type'    => 'Bearer',
            'app_id'        => 'APP-80W284485P519543T',
            'expires_in'    => 32400,
            'nonce'         => 'some_nonce',
        ];

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v1/oauth2/token?grant_type=client_credentials';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
            ],
            'auth' => ['username', 'password'],
        ];

        $mockHttpClient = $this->mock_http_request(json_encode($expectedResponse), $expectedEndpoint, $expectedParams);

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
