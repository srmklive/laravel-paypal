<?php

namespace Srmklive\PayPal\Tests\Feature;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class AdapterFeatureTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @var string */
    protected static $access_token = '';

    /** @var string */
    protected static $product_id = '';

    /** @var \Srmklive\PayPal\Services\PayPal */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new PayPalClient($this->getApiCredentials());

        parent::setUp();
    }

    /** @test */
    public function it_returns_error_if_invalid_credentials_are_used_to_get_access_token()
    {
        $this->client = new PayPalClient($this->getMockCredentials());
        $response = $this->client->getAccessToken();

        $this->assertIsArray($response['error']);
        $this->assertArrayHasKey('error', $response);
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
    public function it_can_create_a_billing_plan()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreatePlansResponse()
            )
        );

        $expectedParams = $this->createPlanParams();

        $response = $this->client->createPlan($expectedParams, 'some-request-id');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_list_billing_plans()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListPlansResponse()
            )
        );

        $response = $this->client->listPlans();

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('plans', $response);
    }

    /** @test */
    public function it_can_update_a_billing_plan()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $expectedParams = $this->updatePlanParams();

        $response = $this->client->updatePlan('P-7GL4271244454362WXNWU5NQ', $expectedParams);

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_show_details_for_a_billing_plan()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetPlansResponse()
            )
        );

        $response = $this->client->showPlanDetails('P-5ML4271244454362WXNWU5NQ');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_activate_a_billing_plan()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->activatePlan('P-7GL4271244454362WXNWU5NQ');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_deactivate_a_billing_plan()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->deactivatePlan('P-7GL4271244454362WXNWU5NQ');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_update_pricing_for_a_billing_plan()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $expectedParams = $this->updatePlanPricingParams();

        $response = $this->client->updatePlanPricing('P-2UF78835G6983425GLSM44MA', $expectedParams);

        $this->assertEmpty($response);
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

        $response = $this->client->updateProduct(self::$product_id, $expectedParams);

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
    public function it_can_accept_dispute_claim()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockAcceptDisputesClaimResponse()
            )
        );

        $response = $this->client->acceptDisputeClaim(
            'PP-D-4012',
            'Full refund to the customer.'
        );

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('links', $response);
    }

    /** @test */
    public function it_can_accept_dispute_offer_resolution()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockAcceptDisputesClaimResponse()
            )
        );

        $response = $this->client->acceptDisputeOfferResolution(
            'PP-D-4012',
            'I am ok with the refund offered.'
        );

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('links', $response);
    }

    /** @test */
    public function it_can_acknowledge_item_is_returned_for_raised_dispute()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockAcceptDisputesClaimResponse()
            )
        );

        $response = $this->client->acknowledgeItemReturned(
            'PP-D-4012',
            'I have received the item back.',
            'ITEM_RECEIVED'
        );

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('links', $response);
    }

    /** @test */
    public function it_can_generate_unique_invoice_number()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGenerateInvoiceNumberResponse()
            )
        );

        $response = $this->client->generateInvoiceNumber();

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('invoice_number', $response);
    }

    /** @test */
    public function it_can_create_a_draft_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreateInvoicesResponse()
            )
        );

        $expectedParams = $this->createInvoiceParams();

        $response = $this->client->createInvoice($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
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
    public function it_can_delete_an_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->deleteInvoice('INV2-Z56S-5LLA-Q52L-CPZ5');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_update_an_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockUpdateInvoicesResponse()
            )
        );

        $expectedParams = $this->updateInvoiceParams();

        $response = $this->client->updateInvoice('INV2-Z56S-5LLA-Q52L-CPZ5', $expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_show_details_for_an_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetInvoicesResponse()
            )
        );

        $response = $this->client->showInvoiceDetails('INV2-Z56S-5LLA-Q52L-CPZ5');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_cancel_an_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $expectedParams = $this->cancelInvoiceParams();

        $response = $this->client->cancelInvoice('INV2-Z56S-5LLA-Q52L-CPZ5', $expectedParams);

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_generate_qr_code_for_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGenerateInvoiceQRCodeResponse()
            )
        );

        $response = $this->client->generateQRCodeInvoice('INV2-Z56S-5LLA-Q52L-CPZ5');

        $this->assertNotEmpty($response);
    }

    /** @test */
    public function it_can_register_payment_for_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockInvoiceRegisterPaymentResponse()
            )
        );

        $response = $this->client->registerPaymentInvoice('INV2-Z56S-5LLA-Q52L-CPZ5', '2018-05-01', 'BANK_TRANSFER', 10.00);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('payment_id', $response);
    }

    /** @test */
    public function it_can_delete_payment_for_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->deleteExternalPaymentInvoice('INV2-Z56S-5LLA-Q52L-CPZ5', 'EXTR-86F38350LX4353815');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_refund_payment_for_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockInvoiceRefundPaymentResponse()
            )
        );

        $response = $this->client->refundInvoice('INV2-Z56S-5LLA-Q52L-CPZ5', '2018-05-01', 'BANK_TRANSFER', 5.00);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('refund_id', $response);
    }

    /** @test */
    public function it_can_delete_refund_for_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->deleteRefundInvoice('INV2-Z56S-5LLA-Q52L-CPZ5', 'EXTR-2LG703375E477444T');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_send_an_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->sendInvoice(
            'INV2-Z56S-5LLA-Q52L-CPZ5',
            'Payment due for the invoice #ABC-123',
            'Please pay before the due date to avoid incurring late payment charges which will be adjusted in the next bill generated.',
            true,
            true,
            [
                'customer-a@example.com',
                'customer@example.com',
            ]
        );

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_send_reminder_for_an_invoice()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->sendInvoiceReminder(
            'INV2-Z56S-5LLA-Q52L-CPZ5',
            'Reminder: Payment due for the invoice #ABC-123',
            'Please pay before the due date to avoid incurring late payment charges which will be adjusted in the next bill generated.',
            true,
            true,
            [
                'customer-a@example.com',
                'customer@example.com',
            ]
        );

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_create_invoice_template()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreateInvoiceTemplateResponse()
            )
        );

        $expectedParams = $this->mockCreateInvoiceTemplateParams();

        $response = $this->client->createInvoiceTemplate($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_list_invoice_templates()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListInvoiceTemplateResponse()
            )
        );

        $response = $this->client->listInvoiceTemplates();

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('templates', $response);
    }

    /** @test */
    public function it_can_delete_an_invoice_template()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->deleteInvoiceTemplate('TEMP-19V05281TU309413B');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_update_an_invoice_template()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockUpdateInvoiceTemplateResponse()
            )
        );

        $expectedParams = $this->mockUpdateInvoiceTemplateParams();

        $response = $this->client->updateInvoiceTemplate('TEMP-19V05281TU309413B', $expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_get_details_for_an_invoice_template()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetInvoiceTemplateResponse()
            )
        );

        $response = $this->client->showInvoiceTemplateDetails('TEMP-19V05281TU309413B');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
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

        $response = $this->client->searchInvoices();

        $this->assertArrayHasKey('total_pages', $response);
        $this->assertArrayHasKey('total_items', $response);
    }

    /** @test */
    public function it_can_search_invoices_with_custom_filters()
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

        $response = $this->client
            ->addInvoiceFilterByRecipientEmail('bill-me@example.com')
            ->addInvoiceFilterByRecipientFirstName('John')
            ->addInvoiceFilterByRecipientLastName('Doe')
            ->addInvoiceFilterByRecipientBusinessName('Acme Inc.')
            ->addInvoiceFilterByInvoiceNumber('#123')
            ->addInvoiceFilterByInvoiceStatus(['PAID', 'MARKED_AS_PAID'])
            ->addInvoiceFilterByReferenceorMemo('deal-ref')
            ->addInvoiceFilterByCurrencyCode('USD')
            ->addInvoiceFilterByAmountRange(30, 50)
            ->addInvoiceFilterByDateRange('2018-06-01', '2018-06-21', 'invoice_date')
            ->addInvoiceFilterByArchivedStatus(false)
            ->addInvoiceFilterByFields(['items', 'payments', 'refunds'])
            ->searchInvoices();

        $this->assertArrayHasKey('total_pages', $response);
        $this->assertArrayHasKey('total_items', $response);
        $this->assertArrayHasKey('items', $response);
    }

    /** @test */
    public function it_throws_exception_on_search_invoices_with_invalid_status()
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

        $this->expectException(\Exception::class);

        $response = $this->client
            ->addInvoiceFilterByInvoiceStatus(['DECLINED'])
            ->searchInvoices();
    }

    /** @test */
    public function it_throws_exception_on_search_invoices_with_invalid_amount_ranges()
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

        $this->expectException(\Exception::class);

        $response = $this->client
            ->addInvoiceFilterByAmountRange(50, 30)
            ->searchInvoices();
    }

    /** @test */
    public function it_throws_exception_on_search_invoices_with_invalid_date_ranges()
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

        $this->expectException(\Exception::class);

        $response = $this->client
            ->addInvoiceFilterByDateRange('2018-07-01', '2018-06-21', 'invoice_date')
            ->searchInvoices();
    }

    /** @test */
    public function it_throws_exception_on_search_invoices_with_invalid_date_range_type()
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

        $this->expectException(\Exception::class);

        $response = $this->client
            ->addInvoiceFilterByDateRange('2018-06-01', '2018-06-21', 'declined_date')
            ->searchInvoices();
    }

    /** @test */
    public function it_can_get_user_profile_details()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockShowProfileInfoResponse()
            )
        );

        $response = $this->client->showProfileInfo();

        $this->assertArrayHasKey('user_id', $response);
        $this->assertArrayHasKey('payer_id', $response);
        $this->assertArrayHasKey('emails', $response);
    }

    /** @test */
    public function it_can_create_merchant_applications()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreateMerchantApplicationResponse()
            )
        );

        $response = $this->client->createMerchantApplication(
            'AGGREGATOR',
            [
                'https://example.com/callback',
                'https://example.com/callback2',
            ],
            [
                'facilitator@example.com',
                'merchant@example.com',
            ],
            'WDJJHEBZ4X2LY',
            'some-open-id'
        );

        $this->assertArrayHasKey('client_name', $response);
        $this->assertArrayHasKey('contacts', $response);
        $this->assertArrayHasKey('redirect_uris', $response);
    }

    /** @test */
    public function it_can_set_account_properties()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client('')
        );

        $response = $this->client->setAccountProperties($this->mockSetAccountPropertiesParams());

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_disable_account_properties()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockUpdateOrdersResponse()
            )
        );

        $response = $this->client->disableAccountProperties();

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_get_client_token()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetClientTokenResponse()
            )
        );

        $response = $this->client->getClientToken();

        $this->assertArrayHasKey('client_token', $response);
    }

    /** @test  */
    public function it_can_create_orders()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreateOrdersResponse()
            )
        );

        $filters = $this->createOrderParams();

        $response = $this->client->createOrder($filters);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('links', $response);
    }

    /** @test  */
    public function it_can_update_orders()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockUpdateOrdersResponse()
            )
        );

        $order_id = '5O190127TN364715T';
        $filters = $this->updateOrderParams();

        $response = $this->client->updateOrder($order_id, $filters);

        $this->assertNotEmpty($response);
    }

    /** @test  */
    public function it_can_get_order_details()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockOrderDetailsResponse()
            )
        );

        $order_id = '5O190127TN364715T';
        $response = $this->client->showOrderDetails($order_id);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('intent', $response);
        $this->assertArrayHasKey('payment_source', $response);
        $this->assertArrayHasKey('purchase_units', $response);
        $this->assertArrayHasKey('create_time', $response);
        $this->assertArrayHasKey('links', $response);
    }

    /** @test  */
    public function it_can_authorize_payment_for_an_order()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockOrderPaymentAuthorizedResponse()
            )
        );

        $order_id = '5O190127TN364715T';
        $response = $this->client->authorizePaymentOrder($order_id);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('payer', $response);
        $this->assertArrayHasKey('purchase_units', $response);
        $this->assertArrayHasKey('links', $response);
    }

    /** @test */
    public function it_can_create_partner_referral()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreatePartnerReferralsResponse()
            )
        );

        $expectedParams = $this->mockCreatePartnerReferralParams();

        $response = $this->client->createPartnerReferral($expectedParams);

        $this->assertArrayHasKey('links', $response);
    }

    /** @test */
    public function it_can_get_referral_details()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockShowReferralDataResponse()
            )
        );

        $partner_referral_id = 'ZjcyODU4ZWYtYTA1OC00ODIwLTk2M2EtOTZkZWQ4NmQwYzI3RU12cE5xa0xMRmk1NWxFSVJIT1JlTFdSbElCbFU1Q3lhdGhESzVQcU9iRT0=';

        $response = $this->client->showReferralData($partner_referral_id);

        $this->assertArrayHasKey('partner_referral_id', $response);
        $this->assertArrayHasKey('referral_data', $response);
    }

    /** @test */
    public function it_can_list_web_experience_profiles()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListWebProfilesResponse()
            )
        );

        $response = $this->client->listWebExperienceProfiles();

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', collect($response)->first());
    }

    /** @test */
    public function it_can_create_web_experience_profile()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockWebProfileResponse()
            )
        );

        $expectedParams = $this->mockCreateWebProfileParams();

        $response = $this->client->createWebExperienceProfile($expectedParams, 'some-request-id');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('name', $response);
    }

    /** @test */
    public function it_can_delete_web_experience_profile()
    {
        $expectedResponse = '';

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $expectedParams = 'XP-A88A-LYLW-8Y3X-E5ER';

        $response = $this->client->deleteWebExperienceProfile($expectedParams);

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_partially_update_web_experience_profile()
    {
        $expectedResponse = '';

        $expectedParams = $this->partiallyUpdateWebProfileParams();

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->patchWebExperienceProfile('XP-A88A-LYLW-8Y3X-E5ER', $expectedParams);

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_fully_update_web_experience_profile()
    {
        $expectedResponse = '';

        $expectedParams = $this->updateWebProfileParams();

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->updateWebExperienceProfile('XP-A88A-LYLW-8Y3X-E5ER', $expectedParams);

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_get_web_experience_profile_details()
    {
        $expectedResponse = $this->mockWebProfileResponse();

        $expectedParams = 'XP-A88A-LYLW-8Y3X-E5ER';

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->showWebExperienceProfileDetails($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('name', $response);
    }

    /** @test  */
    public function it_can_capture_payment_for_an_order()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockOrderPaymentCapturedResponse()
            )
        );

        $order_id = '5O190127TN364715T';
        $response = $this->client->capturePaymentOrder($order_id);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('payer', $response);
        $this->assertArrayHasKey('purchase_units', $response);
        $this->assertArrayHasKey('links', $response);
    }

    /** @test */
    public function it_can_show_details_for_an_authorized_payment()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetAuthorizedPaymentDetailsResponse()
            )
        );

        $response = $this->client->showAuthorizedPaymentDetails('0VF52814937998046');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_capture_an_authorized_payment()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCaptureAuthorizedPaymentResponse()
            )
        );

        $response = $this->client->captureAuthorizedPayment(
            '0VF52814937998046',
            'INVOICE-123',
            10.99,
            'Payment is due'
        );

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_reauthorize_an_authorized_payment()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockReAuthorizeAuthorizedPaymentResponse()
            )
        );

        $response = $this->client->reAuthorizeAuthorizedPayment('0VF52814937998046', 10.99);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_void_an_authorized_payment()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->voidAuthorizedPayment('0VF52814937998046');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_show_details_for_a_captured_payment()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetCapturedPaymentDetailsResponse()
            )
        );

        $response = $this->client->showCapturedPaymentDetails('2GG279541U471931P');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_refund_a_captured_payment()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockRefundCapturedPaymentResponse()
            )
        );

        $response = $this->client->refundCapturedPayment(
            '2GG279541U471931P',
            'INVOICE-123',
            10.99,
            'Defective product'
        );

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_show_details_for_a_refund()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetRefundDetailsResponse()
            )
        );

        $response = $this->client->showRefundDetails('1JU08902781691411');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_create_batch_payout()
    {
        $expectedResponse = $this->mockCreateBatchPayoutResponse();

        $expectedParams = $this->mockCreateBatchPayoutParams();

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->createBatchPayout($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('batch_header', $response);
    }

    /** @test */
    public function it_can_show_batch_payout_details()
    {
        $expectedResponse = $this->showBatchPayoutResponse();

        $expectedParams = 'FYXMPQTX4JC9N';

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->showBatchPayoutDetails($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('batch_header', $response);
        $this->assertArrayHasKey('items', $response);
    }

    /** @test */
    public function it_can_show_batch_payout_item_details()
    {
        $expectedResponse = $this->showBatchPayoutItemResponse();

        $expectedParams = '8AELMXH8UB2P8';

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->showPayoutItemDetails($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('payout_item_id', $response);
        $this->assertArrayHasKey('payout_batch_id', $response);
        $this->assertArrayHasKey('payout_item', $response);
    }

    /** @test */
    public function it_can_cancel_unclaimed_batch_payout_item()
    {
        $expectedResponse = $this->mockCancelUnclaimedBatchItemResponse();

        $expectedParams = '8AELMXH8UB2P8';

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->cancelUnclaimedPayoutItem($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('payout_item_id', $response);
        $this->assertArrayHasKey('payout_batch_id', $response);
        $this->assertArrayHasKey('payout_item', $response);
    }

    /** @test */
    public function it_can_create_referenced_batch_payout()
    {
        $expectedResponse = $this->mockCreateReferencedBatchPayoutResponse();

        $expectedParams = $this->mockCreateReferencedBatchPayoutParams();

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->createReferencedBatchPayout($expectedParams, 'some-request-id', 'some-attribution-id');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('links', $response);
    }

    /** @test */
    public function it_can_list_items_referenced_in_batch_payout()
    {
        $expectedResponse = $this->mockShowReferencedBatchPayoutResponse();

        $expectedParams = 'KHbwO28lWlXwi2IlToJ2IYNG4juFv6kpbFx4J9oQ5Hb24RSp96Dk5FudVHd6v4E=';

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->listItemsReferencedInBatchPayout($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('links', $response);
    }

    /** @test */
    public function it_can_create_referenced_batch_payout_item()
    {
        $expectedResponse = $this->mockCreateReferencedBatchPayoutItemResponse();

        $expectedParams = $this->mockCreateReferencedBatchPayoutItemParams();

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->createReferencedBatchPayoutItem($expectedParams, 'some-request-id', 'some-attribution-id');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('links', $response);
    }

    /** @test */
    public function it_can_show_referenced_payout_item_details()
    {
        $expectedResponse = $this->mockShowReferencedBatchPayoutItemResponse();

        $expectedParams = 'CDZEC5MJ8R5HY';

        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client($expectedResponse)
        );

        $response = $this->client->showReferencedPayoutItemDetails($expectedParams, 'some-attribution-id');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('item_id', $response);
        $this->assertArrayHasKey('reference_id', $response);
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

    /** @test */
    public function it_can_list_account_balances_for_a_different_currency()
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

        $response = $this->client->listBalances($date, 'EUR');

        $this->assertNotEmpty($response);
    }

    /** @test */
    public function it_can_create_a_subscription()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreateSubscriptionResponse()
            )
        );

        $expectedParams = $this->mockCreateSubscriptionParams();

        $response = $this->client->createSubscription($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_update_a_subscription()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $expectedParams = $this->mockUpdateSubscriptionParams();

        $response = $this->client->updateSubscription('I-BW452GLLEP1G', $expectedParams);

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_show_details_for_a_subscription()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetSubscriptionDetailsResponse()
            )
        );

        $response = $this->client->showSubscriptionDetails('I-BW452GLLEP1G');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_activate_a_subscription()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->activateSubscription('I-BW452GLLEP1G', 'Reactivating the subscription');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_cancel_a_subscription()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->cancelSubscription('I-BW452GLLEP1G', 'Not satisfied with the service');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_suspend_a_subscription()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->suspendSubscription('I-BW452GLLEP1G', 'Item out of stock');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_capture_payment_for_a_subscription()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->captureSubscriptionPayment('I-BW452GLLEP1G', 'Charging as the balance reached the limit', 100);

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_update_quantity_or_product_for_a_subscription()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockUpdateSubscriptionItemsResponse()
            )
        );

        $expectedParams = $this->mockUpdateSubscriptionItemsParams();

        $response = $this->client->reviseSubscription('I-BW452GLLEP1G', $expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('plan_id', $response);
    }

    /** @test */
    public function it_can_list_transactions_for_a_subscription()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListSubscriptionTransactionsResponse()
            )
        );

        $response = $this->client->listSubscriptionTransactions('I-BW452GLLEP1G', '2018-01-21T07:50:20.940Z', '2018-08-22T07:50:20.940Z');

        $this->assertNotEmpty($response);
        $this->assertEquals($response, $this->mockListSubscriptionTransactionsResponse());
    }

    /** @test */
    public function it_can_get_tracking_details_for_tracking_id()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetTrackingDetailsResponse()
            )
        );

        $response = $this->client->showTrackingDetails('8MC585209K746392H-443844607820');

        $this->assertNotEmpty($response);
        $this->assertEquals($response, $this->mockGetTrackingDetailsResponse());
        $this->assertArrayHasKey('tracking_number', $response);
    }

    /** @test */
    public function it_can_update_tracking_details_for_tracking_id()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->updateTrackingDetails(
            '8MC585209K746392H-443844607820',
            $this->mockUpdateTrackingDetailsParams()
        );

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_create_tracking_in_batches()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreateTrackinginBatchesResponse()
            )
        );

        $expectedParams = $this->mockCreateTrackinginBatchesParams();

        $response = $this->client->addBatchTracking($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('tracker_identifiers', $response);
    }

    /** @test */
    public function it_can_list_web_hooks_event_types()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListWebHookEventsTypesResponse()
            )
        );

        $response = $this->client->listEventTypes();

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('event_types', $response);
    }

    /** @test */
    public function it_can_list_web_hooks_events()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockWebHookEventsListResponse()
            )
        );

        $response = $this->client->listEvents();

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('events', $response);
    }

    /** @test */
    public function it_can_show_details_for_a_web_hooks_event()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetWebHookEventResponse()
            )
        );

        $response = $this->client->showEventDetails('8PT597110X687430LKGECATA');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_resend_notification_for_a_web_hooks_event()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockResendWebHookEventNotificationResponse()
            )
        );

        $expectedParams = ['12334456'];

        $response = $this->client->resendEventNotification('8PT597110X687430LKGECATA', $expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
    }

    /** @test */
    public function it_can_create_a_web_hook()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreateWebHookResponse()
            )
        );

        $response = $this->client->createWebHook(
            'https://example.com/example_webhook',
            ['PAYMENT.AUTHORIZATION.CREATED', 'PAYMENT.AUTHORIZATION.VOIDED']
        );

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('event_types', $response);
    }

    /** @test */
    public function it_can_list_web_hooks()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListWebHookResponse()
            )
        );

        $response = $this->client->listWebHooks();

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('webhooks', $response);
    }

    /** @test */
    public function it_can_delete_a_web_hook()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(false)
        );

        $response = $this->client->deleteWebHook('5GP028458E2496506');

        $this->assertEmpty($response);
    }

    /** @test */
    public function it_can_update_a_web_hook()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockUpdateWebHookResponse()
            )
        );

        $expectedParams = $this->mockUpdateWebHookParams();

        $response = $this->client->updateWebHook('0EH40505U7160970P', $expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('event_types', $response);
    }

    /** @test */
    public function it_can_show_details_for_a_web_hook()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockGetWebHookResponse()
            )
        );

        $response = $this->client->showWebHookDetails('0EH40505U7160970P');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('event_types', $response);
    }

    /** @test */
    public function it_can_list_events_for_web_hooks()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockListWebHookEventsResponse()
            )
        );

        $response = $this->client->listWebHookEvents('0EH40505U7160970P');

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('event_types', $response);
    }

    /** @test */
    public function it_can_verify_web_hook_signature()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockVerifyWebHookSignatureResponse()
            )
        );

        $expectedParams = $this->mockVerifyWebHookSignatureParams();

        $response = $this->client->verifyWebHook($expectedParams);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('verification_status', $response);
    }
}
