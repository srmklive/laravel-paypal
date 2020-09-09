<?php

namespace Srmklive\PayPal\Tests\Mocks\Responses;

trait Invoices
{
    /**
     * @return array
     */
    private function mockListInvoicesResponse()
    {
        return \GuzzleHttp\json_decode('{
  "total_items": 2,
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
          "recipient_view_url": "https://www.paypal.com/invoice/p/#Z56S5LLAQ52LCPZ5",
          "invoicer_view_url": "https://www.paypal.com/invoice/details/INV2-Z56S-5LLA-Q52L-CPZ5"
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
        },
        {
          "href": "https://api.paypal.com/v2/invoicing/invoices/INV2-Z56S-5LLA-Q52L-CPZ5/send",
          "rel": "send",
          "method": "POST"
        },
        {
          "href": "https://api.paypal.com/v2/invoicing/invoices/INV2-Z56S-5LLA-Q52L-CPZ5",
          "rel": "replace",
          "method": "PUT"
        },
        {
          "href": "https://api.paypal.com/v2/invoicing/invoices/INV2-Z56S-5LLA-Q52L-CPZ5",
          "rel": "delete",
          "method": "DELETE"
        }
      ]
    },
    {
      "id": "INV2-NP6M-C9A8-ZBDA-3TEX",
      "status": "SCHEDULED",
      "detail": {
        "invoice_number": "0001",
        "invoice_date": "2018-05-14",
        "currency_code": "USD",
        "payment_term": {
          "due_date": "2018-05-15"
        },
        "metadata": {
          "create_time": "2018-05-15T17:24:12Z"
        }
      },
      "invoicer": {
        "email_address": "merchant@example.com"
      },
      "primary_recipients": [
        {
          "billing_info": {
            "email_address": "recipient@example.com"
          }
        }
      ],
      "amount": {
        "currency_code": "USD",
        "value": "32.00"
      },
      "links": [
        {
          "href": "https://api.paypal.com/v2/invoicing/invoices/INV2-NP6M-C9A8-ZBDA-3TEX",
          "rel": "self",
          "method": "GET"
        },
        {
          "href": "https://api.paypal.com/v2/invoicing/invoices/INV2-NP6M-C9A8-ZBDA-3TEX",
          "rel": "replace",
          "method": "PUT"
        },
        {
          "href": "https://api.paypal.com/v2/invoicing/invoices/INV2-NP6M-C9A8-ZBDA-3TEX",
          "rel": "delete",
          "method": "DELETE"
        },
        {
          "href": "https://api.paypal.com/v2/invoicing/invoices/INV2-NP6M-C9A8-ZBDA-3TEX/payments",
          "rel": "record-payment",
          "method": "POST"
        }
      ]
    }
  ],
  "links": [
    {
      "href": "https://api.paypal.com/v2/invoicing/invoices?page=1&page_size=20&total_required=false",
      "rel": "self",
      "method": "GET"
    }
  ]
}', true);
    }
}
