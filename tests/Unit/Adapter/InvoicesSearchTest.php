<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class InvoicesSearchTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_search_invoices()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "total_items": 6,
  "total_pages": 1,
  "items": [
    {
      "id": "INV2-Z56S-5LLA-Q52L-CPZ5",
      "status": "DRAFT",
      "detail": {
        "invoice_number": "#123",
        "reference": "deal-ref",
        "invoice_date": "2018-11-12",
        "currency_code": "USD",
        "note": "Thank you for your business.",
        "term": "No refunds after 30 days.",
        "memo": "This is a long contract",
        "payment_term": {
          "term_type": "NET_10",
          "due_date": "2018-11-22"
        },
        "metadata": {
          "create_time": "2018-11-12T08:00:20Z",
          "recipient_view_url": "https://www.api.paypal.com/invoice/p#Z56S5LLAQ52LCPZ5",
          "invoicer_view_url": "https://www.api.paypal.com/invoice/details/INV2-Z56S-5LLA-Q52L-CPZ5"
        }
      },
      "invoicer": {
        "email_address": "merchant@example.com"
      },
      "primary_recipients": [
        {
          "billing_info": {
            "email_address": "bill-me@example.com"
          }
        }
      ],
      "amount": {
        "currency_code": "USD",
        "value": "74.21"
      },
      "links": [
        {
          "href": "https://api.paypal.com/v2/invoicing/invoices/INV2-Z56S-5LLA-Q52L-CPZ5",
          "rel": "self",
          "method": "GET"
        }
      ]
    }
  ],
  "links": [
    {
      "href": "https://api.paypal.com/v2/invoicing/invoices?page=2&page_size=10&total_required=true",
      "rel": "next",
      "method": "POST"
    }
  ]
}', true);

        $expectedParams = \GuzzleHttp\json_decode('{
  "total_amount_range": {
    "lower_amount": {
      "currency_code": "USD",
      "value": "50.00"
    },
    "upper_amount": {
      "currency_code": "USD",
      "value": "50.00"
    }
  },
  "invoice_date_range": {
    "start": "2018-06-01",
    "end": "2018-06-21"
  }
}', true);

        $expectedMethod = 'searchInvoices';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, 1, 1, true));
    }
}
