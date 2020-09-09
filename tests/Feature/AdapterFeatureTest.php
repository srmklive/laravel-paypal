<?php

namespace Srmklive\PayPal\Tests\Feature;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\RequestPayloads;
use Srmklive\PayPal\Tests\ResponsePayloads;

class AdapterFeatureTest extends TestCase
{
    use MockClientClasses;
    use RequestPayloads;
    use ResponsePayloads;

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
        $this->client->setClient(
            $this->mock_http_client(
                $this->mockAccessTokenResponse()
            )
        );
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

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListCatalogProductsResponse()
            )
        );

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

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreateCatalogProductsResponse()
            )
        );

        $expectedParams = $this->createProductParams();

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

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $expectedParams = $this->updateProductParams();

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

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetCatalogProductsResponse()
            )
        );

        $response = $this->client->showProductDetails(self::$product_id);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_list_disputes()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListDisputesResponse()
            )
        );

        $response = $this->client->listDisputes();

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('items', $response);
    }

    /** @test */
    public function it_can_partially_update_a_dispute()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $expectedParams = $this->updateDisputeParams();

        $response = $this->client->updateDispute($expectedParams, 'PP-D-27803');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_get_details_for_a_dispute()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetDisputesResponse()
            )
        );

        $response = $this->client->showDisputeDetails('PP-D-4012');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('dispute_id', $response);
    }

    /** @test */
    public function it_can_list_invoices()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListInvoicesResponse()
            )
        );

        $response = $this->client->listInvoices();

        $this->assertArrayHasKey('total_pages', $response);
        $this->assertArrayHasKey('total_items', $response);
    }

    /** @test */
    public function it_can_search_invoices()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockSearchInvoicesResponse()
            )
        );

        $filters = $this->invoiceSearchParams();

        $response = $this->client->searchInvoices($filters);

        $this->assertArrayHasKey('total_pages', $response);
        $this->assertArrayHasKey('total_items', $response);
    }

    /** @test */
    public function it_can_list_transactions()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListTransactionsResponse()
            )
        );

        $filters = [
            'start_date'    => Carbon::now()->toIso8601String(),
            'end_date'      => Carbon::now()->subDays(30)->toIso8601String(),
        ];

        $response = $this->client->listTransactions($filters);

        $this->assertArrayHasKey('transaction_details', $response);
        $this->assertGreaterThan(0, sizeof($response['transaction_details']));
    }

    /** @test */
    public function it_can_list_account_balances()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListBalancesResponse()
            )
        );

        $date = Carbon::now()->subDays(30)->toIso8601String();

        $response = $this->client->listBalances($date);

        $this->assertNotEmpty($response);
    }
}
