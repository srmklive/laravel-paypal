<?php

namespace Srmklive\PayPal\Tests;

trait RequestPayloads
{
    /**
     * @return array
     */
    protected function createProductParams()
    {
        return \GuzzleHttp\json_decode('{
          "name": "Video Streaming Service",
          "description": "Video streaming service",
          "type": "SERVICE",
          "category": "SOFTWARE",
          "image_url": "https://example.com/streaming.jpg",
          "home_url": "https://example.com/home"
        }', true);
    }

    /**
     * @return array
     */
    protected function updateProductParams()
    {
        return \GuzzleHttp\json_decode('[
          {
            "op": "replace",
            "path": "/description",
            "value": "Premium video streaming service"
          }
        ]', true);
    }

    /**
     * @return array
     */
    protected function updateDisputeParams()
    {
        return \GuzzleHttp\json_decode('[
  {
    "op": "add",
    "path": "/partner_actions/-",
    "value": {
      "id": "AMX-22345",
      "name": "ACCEPT_DISPUTE",
      "create_time": "2018-01-12T10:41:35.000Z",
      "status": "PENDING"
    }
  }
]', true);
    }

    /**
     * @return array
     */
    protected function invoiceSearchParams()
    {
        return \GuzzleHttp\json_decode('{
            "total_amount_range": {
                "lower_amount": {
                    "currency_code": "USD",
                    "value": "20.00"
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
    }
}
