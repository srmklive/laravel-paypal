<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class CatalogProductsTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_create_a_product()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "PROD-XYAB12ABSB7868434",
  "name": "Video Streaming Service",
  "description": "Video streaming service",
  "type": "SERVICE",
  "category": "SOFTWARE",
  "image_url": "https://example.com/streaming.jpg",
  "home_url": "https://example.com/home",
  "create_time": "2020-01-10T21:20:49Z",
  "update_time": "2020-01-10T21:20:49Z",
  "links": [
    {
      "href": "https://api.paypal.com/v1/catalogs/products/72255d4849af8ed6e0df1173",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/catalogs/products/72255d4849af8ed6e0df1173",
      "rel": "edit",
      "method": "PATCH"
    }
  ]
}', true);

        $expectedParams = \GuzzleHttp\json_decode('{
  "name": "Video Streaming Service",
  "description": "Video streaming service",
  "type": "SERVICE",
  "category": "SOFTWARE",
  "image_url": "https://example.com/streaming.jpg",
  "home_url": "https://example.com/home"
}', true);

        $expectedMethod = 'createProduct';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, 'PRODUCT-000-001'));
    }

    /** @test */
    public function it_can_list_products()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "total_items": 20,
  "total_pages": 1,
  "products": [
    {
      "id": "72255d4849af8ed6e0df1173",
      "name": "Video Streaming Service",
      "description": "Video streaming service",
      "create_time": "2018-12-10T21:20:49Z",
      "links": [
        {
          "href": "https://api.paypal.com/v1/catalogs/products/72255d4849af8ed6e0df1173",
          "rel": "self",
          "method": "GET"
        }
      ]
    },
    {
      "id": "PROD-XYAB12ABSB7868434",
      "name": "Video Streaming Service",
      "description": "Audio streaming service",
      "create_time": "2018-12-10T21:20:49Z",
      "links": [
        {
          "href": "https://api.paypal.com/v1/catalogs/products/125d4849af8ed6e0df18",
          "rel": "self",
          "method": "GET"
        }
      ]
    }
  ],
  "links": [
    {
      "href": "https://api.paypal.com/v1/catalogs/products?page_size=2&page=1",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/catalogs/products?page_size=2&page=2",
      "rel": "next",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/catalogs/products?page_size=2&page=10",
      "rel": "last",
      "method": "GET"
    }
  ]
}', true);

        $expectedMethod = 'listProducts';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_update_a_product()
    {
        $expectedResponse = '';

        $expectedParams = \GuzzleHttp\json_decode('[
  {
    "op": "replace",
    "path": "/description",
    "value": "Premium video streaming service"
  }
]', true);

        $expectedMethod = 'updateProduct';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, '72255d4849af8ed6e0df1173'));
    }

    /** @test */
    public function it_can_get_details_for_a_product()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "72255d4849af8ed6e0df1173",
  "name": "Video Streaming Service",
  "description": "Video streaming service",
  "type": "SERVICE",
  "category": "SOFTWARE",
  "image_url": "https://example.com/streaming.jpg",
  "home_url": "https://example.com/home",
  "create_time": "2018-12-10T21:20:49Z",
  "update_time": "2018-12-10T21:20:49Z",
  "links": [
    {
      "href": "https://api.paypal.com/v1/catalogs/products/72255d4849af8ed6e0df1173",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/catalogs/products/72255d4849af8ed6e0df1173",
      "rel": "edit",
      "method": "PATCH"
    }
  ]
}', true);

        $expectedMethod = 'showProductDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('72255d4849af8ed6e0df1173'));
    }
}
