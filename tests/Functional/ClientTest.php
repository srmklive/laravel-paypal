<?php

namespace Srmklive\PayPal\Tests\Functional;

use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class ClientTest extends TestCase
{
    use MockClientClasses;

    /** @var \GuzzleHttp\Client|\Prophecy\Prophecy\ObjectProphecy */
    protected $client;

    protected $response;

    protected $body;

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->mock_http_client('');

        $this->assertInstanceOf(HttpClient::class, $this->client);
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

        $this->mock_http_client($expectedResponse);

        $response = $this->client->post($expectedEndpoint, $expectedParams)->getBody();
        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($response, true));
    }
}
