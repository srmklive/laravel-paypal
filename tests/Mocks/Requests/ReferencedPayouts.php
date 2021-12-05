<?php

namespace Srmklive\PayPal\Tests\Mocks\Requests;

trait ReferencedPayouts
{
    /**
     * @return array
     */
    private function mockCreateReferencedBatchPayoutParams()
    {
        return \GuzzleHttp\json_decode('{
  "referenced_payouts": [
    {
      "reference_id": "2KP03934U4415543C",
      "reference_type": "TRANSACTION_ID"
    },
    {
      "reference_id": "8TA4226978212399L",
      "reference_type": "TRANSACTION_ID"
    }
  ]
}', true);
    }

    /**
     * @return array
     */
    private function mockCreateReferencedBatchPayoutItemParams()
    {
        return \GuzzleHttp\json_decode('{
  "reference_id": "CAPTURETXNID",
  "reference_type": "TRANSACTION_ID"
}', true);
    }
}
