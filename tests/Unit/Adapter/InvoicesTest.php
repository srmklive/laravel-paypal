<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class InvoicesTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_generate_unique_invoice_number()
    {
        $expectedResponse = $this->mockGenerateInvoiceNumberResponse();

        $expectedMethod = 'generateInvoiceNumber';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_create_a_draft_invoice()
    {
        $expectedResponse = $this->mockCreateInvoicesResponse();

        $expectedParams = $this->createInvoiceParams();

        $expectedMethod = 'createInvoice';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }

    /** @test */
    public function it_can_list_current_invoices()
    {
        $expectedResponse = $this->mockListInvoicesResponse();

        $expectedMethod = 'listInvoices';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}(1, 2, true));
    }

    /** @test */
    public function it_can_delete_an_invoice()
    {
        $expectedResponse = '';

        $expectedMethod = 'deleteInvoice';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('INV2-Z56S-5LLA-Q52L-CPZ5'));
    }

    /** @test */
    public function it_can_update_an_invoice()
    {
        $expectedResponse = $this->mockUpdateInvoicesResponse();

        $expectedParams = $this->updateInvoiceParams();

        $expectedMethod = 'updateInvoice';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('INV2-Z56S-5LLA-Q52L-CPZ5', $expectedParams));
    }

    /** @test */
    public function it_can_show_details_for_an_invoice()
    {
        $expectedResponse = $this->mockGetInvoicesResponse();

        $expectedMethod = 'showInvoiceDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('INV2-Z56S-5LLA-Q52L-CPZ5'));
    }

    /** @test */
    public function it_can_cancel_an_invoice()
    {
        $expectedResponse = '';

        $expectedParams = $this->cancelInvoiceParams();

        $expectedMethod = 'cancelInvoice';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('INV2-Z56S-5LLA-Q52L-CPZ5', $expectedParams));
    }

    /** @test */
    public function it_can_generate_qr_code_for_invoice()
    {
        $expectedResponse = $this->mockGenerateInvoiceQRCodeResponse();

        $expectedMethod = 'generateQRCodeInvoice';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('INV2-Z56S-5LLA-Q52L-CPZ5', 400, 400));
    }

    /** @test */
    public function it_can_register_payment_for_invoice()
    {
        $expectedResponse = $this->mockInvoiceRegisterPaymentResponse();

        $expectedMethod = 'registerPaymentInvoice';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('INV2-Z56S-5LLA-Q52L-CPZ5', '2018-05-01', 'BANK_TRANSFER', 10.00));
    }

    /** @test */
    public function it_can_delete_payment_for_invoice()
    {
        $expectedResponse = '';

        $expectedMethod = 'deleteExternalPaymentInvoice';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('INV2-Z56S-5LLA-Q52L-CPZ5', 'EXTR-86F38350LX4353815'));
    }

    /** @test */
    public function it_can_refund_payment_for_invoice()
    {
        $expectedResponse = $this->mockInvoiceRefundPaymentResponse();

        $expectedMethod = 'refundInvoice';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('INV2-Z56S-5LLA-Q52L-CPZ5', '2018-05-01', 'BANK_TRANSFER', 5.00));
    }

    /** @test */
    public function it_can_delete_refund_for_invoice()
    {
        $expectedResponse = '';

        $expectedMethod = 'deleteRefundInvoice';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('INV2-Z56S-5LLA-Q52L-CPZ5', 'EXTR-2LG703375E477444T'));
    }

    /** @test */
    public function it_can_send_an_invoice()
    {
        $expectedResponse = '';

        $expectedMethod = 'sendInvoice';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}(
            'INV2-Z56S-5LLA-Q52L-CPZ5',
            'Reminder: Payment due for the invoice #ABC-123',
            'Please pay before the due date to avoid incurring late payment charges which will be adjusted in the next bill generated.',
            true,
            true,
            [
                'customer-a@example.com',
                'customer@example.com',
            ]
        ));
    }

    /** @test */
    public function it_can_send_reminder_for_an_invoice()
    {
        $expectedResponse = '';

        $expectedMethod = 'sendInvoiceReminder';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}(
            'INV2-Z56S-5LLA-Q52L-CPZ5',
            'Reminder: Payment due for the invoice #ABC-123',
            'Please pay before the due date to avoid incurring late payment charges which will be adjusted in the next bill generated.',
            true,
            true,
            [
                'customer-a@example.com',
                'customer@example.com',
            ]
        ));
    }
}
