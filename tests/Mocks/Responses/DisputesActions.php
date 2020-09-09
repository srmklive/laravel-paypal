<?php

namespace Srmklive\PayPal\Tests\Mocks\Responses;

trait DisputesActions
{
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
}
