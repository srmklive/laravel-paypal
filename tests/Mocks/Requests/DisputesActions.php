<?php

namespace Srmklive\PayPal\Tests\Mocks\Requests;

use GuzzleHttp\Utils;

trait DisputesActions
{
    /**
     * @return array
     */
    protected function acceptDisputeClaimParams()
    {
        return Utils::jsonDecode('{
  "note": "Full refund to the customer.",
  "accept_claim_type": "REFUND"
}', true);
    }

    /**
     * @return array
     */
    protected function acceptDisputeResolutionParams()
    {
        return Utils::jsonDecode('{
  "note": "I am ok with the refund offered."
}', true);
    }

    /**
     * @return array
     */
    protected function acknowledgeItemReturnedParams()
    {
        return Utils::jsonDecode('{
  "note": "I have received the item back.",
  "acknowledgement_type": "ITEM_RECEIVED"
}', true);
    }
}
