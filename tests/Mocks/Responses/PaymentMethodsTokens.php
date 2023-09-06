<?php

namespace Srmklive\PayPal\Tests\Mocks\Responses;

use GuzzleHttp\Utils;

trait PaymentMethodsTokens
{
    /**
     * @return array
     */
    private function mockCreatePaymentMethodsTokenResponse(): array
    {
        return Utils::jsonDecode('{
            "id": "8kk8451t",
            "customer": {
              "id": "customer_4029352050"
            },
            "payment_source": {
              "card": {
                "brand": "VISA",
                "last_digits": "1111",
                "expiry": "2027-02",
                "name": "John Doe",
                "billing_address": {
                  "address_line_1": "2211 N First Street",
                  "address_line_2": "17.3.160",
                  "admin_area_2": "San Jose",
                  "admin_area_1": "CA",
                  "postal_code": "95131",
                  "country_code": "US"
                }
              }
            },
            "links": [
              {
                "rel": "self",
                "href": "https://api-m.paypal.com/v3/vault/payment-tokens/8kk8451t",
                "method": "GET",
                "encType": "application/json"
              },
              {
                "rel": "delete",
                "href": "https://api-m.paypal.com/v3/vault/payment-tokens/8kk8451t",
                "method": "DELETE",
                "encType": "application/json"
              }
            ]
          }', true);        
    }
}