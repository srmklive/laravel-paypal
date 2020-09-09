<?php

namespace Srmklive\PayPal\Tests;

trait ResponsePayloads
{
    /**
     * @return array
     */
    private function mockAccessTokenResponse()
    {
        return [
            'scope'         => 'some_scope',
            'access_token'  => 'some_access_token',
            'token_type'    => 'Bearer',
            'app_id'        => 'APP-80W284485P519543T',
            'expires_in'    => 32400,
            'nonce'         => 'some_nonce',
        ];
    }

    /**
     * @return array
     */
    private function mockCreateCatalogProductsResponse()
    {
        return \GuzzleHttp\json_decode('{
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
    }

    /**
     * @return array
     */
    private function mockListCatalogProductsResponse()
    {
        return \GuzzleHttp\json_decode('{
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
    }

    /**
     * @return array
     */
    private function mockGetCatalogProductsResponse()
    {
        return \GuzzleHttp\json_decode('{
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
    }

    /**
     * @return array
     */
    private function mockListDisputesResponse()
    {
        return \GuzzleHttp\json_decode('{
  "items": [
    {
      "dispute_id": "PP-000-003-648-191",
      "create_time": "2017-01-24T10:41:35.000Z",
      "update_time": "2017-01-24T11:40:32.000Z",
      "status": "WAITING_FOR_SELLER_RESPONSE",
      "reason": "MERCHANDISE_OR_SERVICE_NOT_RECEIVED",
      "dispute_state": "REQUIRED_OTHER_PARTY_ACTION",
      "dispute_amount": {
        "currency_code": "USD",
        "value": "50.00"
      },
      "links": [
        {
          "href": "https://api.sandbox.paypal.com/v1/customer/disputes/PP-000-003-648-191",
          "rel": "self",
          "method": "GET"
        }
      ]
    },
    {
      "dispute_id": "PP-000-003-648-175",
      "create_time": "2017-01-24T10:37:23.000Z",
      "update_time": "2017-01-24T11:32:32.000Z",
      "status": "UNDER_REVIEW",
      "reason": "UNAUTHORISED",
      "dispute_amount": {
        "currency_code": "USD",
        "value": "20.00"
      },
      "links": [
        {
          "href": "https://api.sandbox.paypal.com/v1/customer/disputes/PP-000-003-648-175",
          "rel": "self",
          "method": "GET"
        }
      ]
    }
  ],
  "links": [
    {
      "href": "https://api.sandbox.paypal.com/v1/customer/disputes",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.sandbox.paypal.com/v1/customer/disputes",
      "rel": "first",
      "method": "GET"
    }
  ]
}', true);
    }

    /**
     * @return array
     */
    private function mockGetDisputesResponse()
    {
        return \GuzzleHttp\json_decode('{
  "dispute_id": "PP-D-4012",
  "create_time": "2019-04-11T04:18:00.000Z",
  "update_time": "2019-04-21T04:19:08.000Z",
  "disputed_transactions": [
    {
      "seller_transaction_id": "3BC38643YC807283D",
      "create_time": "2019-04-11T04:16:58.000Z",
      "transaction_status": "REVERSED",
      "gross_amount": {
        "currency_code": "USD",
        "value": "192.00"
      },
      "buyer": {
        "name": "Lupe Justin"
      },
      "seller": {
        "email": "merchant@example.com",
        "merchant_id": "5U29WL78XSAEL",
        "name": "Lesley Paul"
      }
    }
  ],
  "reason": "MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED",
  "status": "RESOLVED",
  "dispute_amount": {
    "currency_code": "USD",
    "value": "96.00"
  },
  "dispute_outcome": {
    "outcome_code": "RESOLVED_BUYER_FAVOUR",
    "amount_refunded": {
      "currency_code": "USD",
      "value": "96.00"
    }
  },
  "dispute_life_cycle_stage": "CHARGEBACK",
  "dispute_channel": "INTERNAL",
  "messages": [
    {
      "posted_by": "BUYER",
      "time_posted": "2019-04-11T04:18:04.000Z",
      "content": "SNAD case created through automation"
    }
  ],
  "extensions": {
    "merchandize_dispute_properties": {
      "issue_type": "SERVICE",
      "service_details": {
        "sub_reasons": [
          "INCOMPLETE"
        ],
        "purchase_url": "https://ebay.in"
      }
    }
  },
  "offer": {
    "buyer_requested_amount": {
      "currency_code": "USD",
      "value": "96.00"
    }
  },
  "links": [
    {
      "href": "https://api.sandbox.paypal.com/v1/customer/disputes/PP-D-4012",
      "rel": "self",
      "method": "GET"
    }
  ]
}', true);
    }

    /**
     * @return array
     */
    private function mockAcceptDisputesClaimResponse()
    {
        return \GuzzleHttp\json_decode('{
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api.sandbox.paypal.com/v1/customer/disputes/PP-D-27803"
    }
  ]
}', true);
    }

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

    /**
     * @return array
     */
    private function mockSearchInvoicesResponse()
    {
        return \GuzzleHttp\json_decode('{
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
    }

    /**
     * @return array
     */
    private function mockListTransactionsResponse()
    {
        return \GuzzleHttp\json_decode('{
  "transaction_details": [
    {
      "transaction_info": {
        "paypal_account_id": "6STWC2LSUYYYE",
        "transaction_id": "5TY05013RG002845M",
        "transaction_event_code": "T0006",
        "transaction_initiation_date": "2014-07-11T04:03:52+0000",
        "transaction_updated_date": "2014-07-11T04:03:52+0000",
        "transaction_amount": {
          "currency_code": "USD",
          "value": "465.00"
        },
        "fee_amount": {
          "currency_code": "USD",
          "value": "-13.79"
        },
        "insurance_amount": {
          "currency_code": "USD",
          "value": "15.00"
        },
        "shipping_amount": {
          "currency_code": "USD",
          "value": "30.00"
        },
        "shipping_discount_amount": {
          "currency_code": "USD",
          "value": "10.00"
        },
        "transaction_status": "S",
        "transaction_subject": "Bill for your purchase",
        "transaction_note": "Check out the latest sales",
        "invoice_id": "Invoice-005",
        "custom_field": "Thank you for your business",
        "protection_eligibility": "01"
      },
      "payer_info": {
        "account_id": "6STWC2LSUYYYE",
        "email_address": "consumer@example.com",
        "address_status": "Y",
        "payer_status": "Y",
        "payer_name": {
          "given_name": "test",
          "surname": "consumer",
          "alternate_full_name": "test consumer"
        },
        "country_code": "US"
      },
      "shipping_info": {
        "name": "Sowmith",
        "address": {
          "line1": "Eco Space, bellandur",
          "line2": "OuterRingRoad",
          "city": "Bangalore",
          "country_code": "IN",
          "postal_code": "560103"
        }
      },
      "cart_info": {
        "item_details": [
          {
            "item_code": "ItemCode-1",
            "item_name": "Item1 - radio",
            "item_description": "Radio",
            "item_quantity": "2",
            "item_unit_price": {
              "currency_code": "USD",
              "value": "50.00"
            },
            "item_amount": {
              "currency_code": "USD",
              "value": "100.00"
            },
            "tax_amounts": [
              {
                "tax_amount": {
                  "currency_code": "USD",
                  "value": "20.00"
                }
              }
            ],
            "total_item_amount": {
              "currency_code": "USD",
              "value": "120.00"
            },
            "invoice_number": "Invoice-005"
          },
          {
            "item_code": "ItemCode-2",
            "item_name": "Item2 - Headset",
            "item_description": "Headset",
            "item_quantity": "3",
            "item_unit_price": {
              "currency_code": "USD",
              "value": "100.00"
            },
            "item_amount": {
              "currency_code": "USD",
              "value": "300.00"
            },
            "tax_amounts": [
              {
                "tax_amount": {
                  "currency_code": "USD",
                  "value": "60.00"
                }
              }
            ],
            "total_item_amount": {
              "currency_code": "USD",
              "value": "360.00"
            },
            "invoice_number": "Invoice-005"
          },
          {
            "item_name": "3",
            "item_quantity": "1",
            "item_unit_price": {
              "currency_code": "USD",
              "value": "-50.00"
            },
            "item_amount": {
              "currency_code": "USD",
              "value": "-50.00"
            },
            "total_item_amount": {
              "currency_code": "USD",
              "value": "-50.00"
            },
            "invoice_number": "Invoice-005"
          }
        ]
      },
      "store_info": {},
      "auction_info": {},
      "incentive_info": {}
    }
  ],
  "account_number": "XZXSPECPDZHZU",
  "last_refreshed_datetime": "2017-01-02T06:59:59+0000",
  "page": 1,
  "total_items": 1,
  "total_pages": 1,
  "links": [
    {
      "href": "https://api.sandbox.paypal.com/v1/reporting/transactions?start_date=2014-07-01T00:00:00-0700&end_date=2014-07-30T23:59:59-0700&transaction_id=5TY05013RG002845M&fields=all&page_size=100&page=1",
      "rel": "self",
      "method": "GET"
    }
  ]
}', true);
    }

    /**
     * @return array
     */
    private function mockListBalancesResponse()
    {
        return \GuzzleHttp\json_decode('{
  "balance": {
    "currency": "USD",
    "primary": true,
    "total_balance": {
      "currency_code": "USD",
      "value": "300.00"
    },
    "available_balance": {
      "currency_code": "USD",
      "value": "100.00"
    },
    "withheld_balance": {
      "currency_code": "USD",
      "value": "200.00"
    }
  },
  "account_id": "QCXKLSS8GWT22",
  "as_of_time": "2016-10-15T13:07:00Z",
  "last_refresh_time": "2017-02-17T05:59:59Z"
}', true);
    }
}
