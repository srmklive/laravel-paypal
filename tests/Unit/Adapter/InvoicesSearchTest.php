<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class InvoicesSearchTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_search_invoices()
    {
        $expectedResponse = $this->mockSearchInvoicesResponse();

        $expectedMethod = 'searchInvoices';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}(1, 1, true));
    }

    /** @test */
    public function it_can_search_invoices_with_custom_filters()
    {
        $expectedResponse = $this->mockSearchInvoicesResponse();

        $expectedMethod = 'searchInvoices';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->addInvoiceFilterByRecipientEmail('bill-me@example.com')
            ->addInvoiceFilterByCurrencyCode('USD')
            ->addInvoiceFilterByAmountRange(30, 50)
            ->{$expectedMethod}(1, 1, true));
    }
}
