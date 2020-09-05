<?php

namespace Srmklive\PayPal\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;

class AdapterFeatureTest extends TestCase
{
    use MockClientClasses;

    /** @var string */
    protected static $access_token = '';

    /** @var string */
    protected static $product_id = '';

    /** @var \Srmklive\PayPal\Services\PayPal */
    protected $client;

    protected function setUp()
    {
        $this->client = new PayPalClient($this->getApiCredentials());

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
        $response = $this->client->getAccessToken();

        self::$access_token = $response['access_token'];

        $this->assertArrayHasKey('access_token', $response);
        $this->assertNotEmpty($response['access_token']);
    }

    /** @test */
    public function it_can_list_products()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $response = $this->client->listProducts();

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('products', $response);
    }

    /** @test */
    public function it_can_create_a_product()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $expectedParams = \GuzzleHttp\json_decode('{
  "name": "Video Streaming Service",
  "description": "Video streaming service",
  "type": "SERVICE",
  "category": "SOFTWARE",
  "image_url": "https://example.com/streaming.jpg",
  "home_url": "https://example.com/home"
}', true);

        $response = $this->client->createProduct($expectedParams, 'product-request-'.time());

        self::$product_id = $response['id'];

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_update_a_product()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $expectedParams = \GuzzleHttp\json_decode('[
          {
            "op": "replace",
            "path": "/description",
            "value": "Premium video streaming service"
          }
        ]', true);

        $response = $this->client->updateProduct($expectedParams, self::$product_id);

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_get_details_for_a_product()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $response = $this->client->showProductDetails(self::$product_id);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }
}
