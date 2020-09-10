<?php

namespace Srmklive\PayPal\Tests\Unit\Client;

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

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v2/invoicing/search-invoices?page=1&page_size=1&total_required=true';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => $this->invoiceSearchParams(),
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
