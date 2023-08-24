<?php

namespace Srmklive\PayPal\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class AdapterTest extends TestCase
{
    use MockClientClasses;
    use MockResponsePayloads;

    /** @test */
    public function it_can_be_instantiated()
    {
        $client = new PayPalClient($this->getMockCredentials());

        $this->assertInstanceOf(PayPalClient::class, $client);
    }

    /** @test */
    public function it_throws_exception_if_invalid_credentials_are_provided()
    {
        $this->expectException(\RuntimeException::class);

        $client = new PayPalClient();
    }

    /** @test */
    public function it_throws_exception_if_invalid_mode_is_provided()
    {
        $this->expectException(\RuntimeException::class);
        // $this->expectErrorMessage('Invalid configuration provided. Please provide valid configuration for PayPal API. You can also refer to the documentation at https://srmklive.github.io/laravel-paypal/docs.html to setup correct configuration.');

        $credentials = $this->getMockCredentials();
        $credentials['mode'] = '';

        $client = new PayPalClient($credentials);
    }

    /** @test */
    public function it_throws_exception_if_empty_credentials_are_provided()
    {
        $this->expectException(\RuntimeException::class);
        // $this->expectErrorMessage('Invalid configuration provided. Please provide valid configuration for PayPal API. You can also refer to the documentation at https://srmklive.github.io/laravel-paypal/docs.html to setup correct configuration.');

        $credentials = $this->getMockCredentials();
        $credentials['sandbox'] = [];

        $client = new PayPalClient($credentials);
    }

    /** @test */
    public function it_throws_exception_if_credentials_items_are_not_provided()
    {
        $item = 'client_id';

        $this->expectException(\RuntimeException::class);
        // $this->expectErrorMessage("{$item} missing from the provided configuration. Please add your application {$item}.");

        $credentials = $this->getMockCredentials();
        $credentials['sandbox'][$item] = '';

        $client = new PayPalClient($credentials);
    }

    /** @test */
    public function it_can_get_access_token()
    {
        $expectedResponse = $this->mockAccessTokenResponse();

        $expectedMethod = 'getAccessToken';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, false);

        $mockClient->setApiCredentials($this->getMockCredentials());

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }
}
