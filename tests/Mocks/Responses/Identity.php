<?php

namespace Srmklive\PayPal\Tests\Mocks\Responses;

use GuzzleHttp\Utils;

trait Identity
{
    private function mockShowProfileInfoResponse(): array
    {
        return Utils::jsonDecode('{
  "address": {
    "street_address": "7917394 Annursnac Hill Road Unit 0C",
    "locality": "Ventura",
    "region": "CA",
    "postal_code": "93003",
    "country": "US"
  }
}', true);
    }
}
