<?php

namespace Srmklive\PayPal\Tests\Mocks\Requests;

trait DisputesActions
{
    /**
     * @return array
     */
    protected function acceptDisputeClaimParams()
    {
        return \GuzzleHttp\json_decode('{
  "note": "Full refund to the customer.",
  "accept_claim_type": "REFUND"
}', true);
    }

    /**
     * @return array
     */
    protected function acceptDisputeResoltuionParams()
    {
        return \GuzzleHttp\json_decode('{
  "note": "I am ok with the refund offered."
}', true);
    }

    /**
     * @return array
     */
    protected function acknowledgeItemReturnedParams()
    {
        return \GuzzleHttp\json_decode('{
  "note": "I have received the item back.",
  "acknowledgement_type": "ITEM_RECEIVED"
}', true);
    }
}
