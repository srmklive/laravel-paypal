<?php

namespace Srmklive\PayPal\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;

class AdapterConfigTest extends TestCase
{
    use MockClientClasses;

    /** @var \Srmklive\PayPal\Services\PayPal */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new PayPalClient($this->getApiCredentials());

        parent::setUp();
    }

    /** @test */
    public function it_throws_exception_if_invalid_credentials_are_provided()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid configuration provided. Please provide valid configuration for PayPal API. You can also refer to the documentation at https://srmklive.github.io/laravel-paypal/docs.html to setup correct configuration.');

        $this->client = new PayPalClient([]);
    }

    /** @test */
    public function it_throws_exception_if_invalid_mode_is_provided()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid configuration provided. Please provide valid configuration for PayPal API. You can also refer to the documentation at https://srmklive.github.io/laravel-paypal/docs.html to setup correct configuration.');

        $credentials = $this->getApiCredentials();
        $credentials['mode'] = '';

        $this->client = new PayPalClient($credentials);
    }

    /** @test */
    public function it_throws_exception_if_empty_credentials_are_provided()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid configuration provided. Please provide valid configuration for PayPal API. You can also refer to the documentation at https://srmklive.github.io/laravel-paypal/docs.html to setup correct configuration.');

        $credentials = $this->getApiCredentials();
        $credentials['sandbox'] = [];

        $this->client = new PayPalClient($credentials);
    }

    /** @test */
    public function it_throws_exception_if_credentials_items_are_not_provided()
    {
        $item = 'client_id';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("{$item} missing from the provided configuration. Please add your application {$item}.");

        $credentials = $this->getApiCredentials();
        $credentials['sandbox'][$item] = '';

        $client = new PayPalClient($credentials);
    }

    /** @test */
    public function it_can_take_valid_credentials_and_return_the_client_instance()
    {
        $this->assertInstanceOf(PayPalClient::class, $this->client);
    }

    /** @test */
    public function it_throws_exception_if_invalid_credentials_are_provided_through_method()
    {
        $this->expectException(\RuntimeException::class);

        $this->client->setApiCredentials([]);
    }

    /** @test */
    public function it_returns_the_client_instance_if_valid_credentials_are_provided_through_method()
    {
        $this->client->setApiCredentials($this->getApiCredentials());

        $this->assertInstanceOf(PayPalClient::class, $this->client);
    }

    /** @test */
    public function it_throws_exception_if_invalid_currency_is_set()
    {
        $this->expectException(\RuntimeException::class);

        $this->client->setCurrency('PKR');

        $this->assertNotEquals('PKR', $this->client->getCurrency());
    }

    /** @test */
    public function it_can_set_a_valid_currency()
    {
        $this->client->setCurrency('EUR');

        $this->assertNotEmpty($this->client->getCurrency());
        $this->assertEquals('EUR', $this->client->getCurrency());
    }

    /** @test */
    public function it_can_set_a_request_header()
    {
        $this->client->setRequestHeader('Prefer', 'return=representation');

        $this->assertNotEmpty($this->client->getRequestHeader('Prefer'));
        $this->assertEquals($this->client->getRequestHeader('Prefer'), 'return=representation');
    }

    /** @test */
    public function it_throws_exception_if_options_header_not_set()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionCode('0');
        $this->expectExceptionMessage('Options header is not set.');

        $this->client->getRequestHeader('Prefer');
    }
}
