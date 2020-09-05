<?php

namespace Srmklive\PayPal\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;

class AdapterFeatureTest extends TestCase
{
    use MockClientClasses;

    /** @var array */
    protected $response;

    /** @var string */
    protected $access_token;

    /** @var string */
    protected static $product_id;

    /** @var \Srmklive\PayPal\Services\PayPal */
    protected $client;

    protected function setUp()
    {
        $this->client = new PayPalClient($this->getApiCredentials());

        $this->response = $this->client->getAccessToken();
        $this->access_token = $this->response['access_token'];

        parent::setUp();
    }

    /** @test */
    public function it_returns_error_if_invalid_credentials_are_used_to_get_access_token()
    {
        $this->client = new PayPalClient($this->getMockCredentials());
        $response = $this->client->getAccessToken();

        $this->assertArrayHasKey('type', $response);
        $this->assertEquals('error', $response['type']);
    }

    /** @test */
    public function it_can_get_access_token()
    {
        $this->assertArrayHasKey('access_token', $this->response);
        $this->assertNotEmpty($this->response['access_token']);
        $this->assertNotEmpty($this->access_token);
    }
}
