<?php

namespace Srmklive\PayPal\Tests\Unit;


use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class AdapterTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $client = new PayPalClient();

        $this->assertInstanceOf(PayPalClient::class, $client);
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
            'nonce'         => 'some_nonce'
        ];

        $expectedMethod = 'getAccessToken';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials());

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    protected static function getMethod($name)
    {
        $class = new \ReflectionClass(Client::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    private function mock_client($expectedResponse, $expectedMethod, $expectedParams)
    {
        $set_method_name = 'setMethods';
        if (function_exists('onlyMethods')) {
            $set_method_name = 'onlyMethods';
        }

        $mockClient = $this->getMockBuilder(PayPalClient::class)
            ->setConstructorArgs($expectedParams)
            ->{$set_method_name}([$expectedMethod])
            ->getMock();
        $mockClient->expects($this->exactly(1))
            ->method($expectedMethod)
            ->willReturn($expectedResponse);

        return $mockClient;
    }

    private function getCredentials()
    {
        return [
            'mode' => 'sandbox',
            'sandbox' => [
                'client_id' => 'some-client-id',
                'client_secret' => 'some-access-token',
                'app_id' => '',
            ],
            'payment_action' => 'Sale',
            'currency'       => 'USD',
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   => true
        ];
    }
}
