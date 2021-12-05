<?php

namespace Srmklive\PayPal\Tests\Mocks\Responses;

use GuzzleHttp\Utils;

trait DisputesActions
{
    /**
     * @return array
     */
    private function mockAcceptDisputesClaimResponse()
    {
        return Utils::jsonDecode('{
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api-m.sandbox.paypal.com/v1/customer/disputes/PP-D-27803"
    }
  ]
}', true);
    }

    /**
     * @return array
     */
    private function mockAcceptDisputesOfferResolutionResponse()
    {
        return Utils::jsonDecode('{
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api-m.sandbox.paypal.com/v1/customer/disputes/PP-000-000-651-454"
    }
  ]
}', true);
    }

    /**
     * @return array
     */
    private function mockAcknowledgeItemReturnedResponse()
    {
        return Utils::jsonDecode('{
  "links": [
    {
      "rel": "self",
      "method": "GET",
      "href": "https://api-m.sandbox.paypal.com/v1/customer/disputes/PP-000-000-651-454"
    }
  ]
}', true);
    }
}
