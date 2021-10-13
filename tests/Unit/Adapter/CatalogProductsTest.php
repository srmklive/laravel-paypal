<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class CatalogProductsTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_create_a_product()
    {
        $expectedResponse = $this->mockCreateCatalogProductsResponse();

        $expectedParams = $this->createProductParams();

        $expectedMethod = 'createProduct';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, 'PRODUCT-000-001'));
    }

    /** @test */
    public function it_can_list_products()
    {
        $expectedResponse = $this->mockListCatalogProductsResponse();

        $expectedMethod = 'listProducts';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_update_a_product()
    {
        $expectedResponse = '';

        $expectedParams = $this->updateProductParams();

        $expectedMethod = 'updateProduct';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('72255d4849af8ed6e0df1173', $expectedParams));
    }

    /** @test */
    public function it_can_get_details_for_a_product()
    {
        $expectedResponse = $this->mockGetCatalogProductsResponse();

        $expectedMethod = 'showProductDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('72255d4849af8ed6e0df1173'));
    }
}
