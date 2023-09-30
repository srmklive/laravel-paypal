<?php

namespace Srmklive\PayPal\Tests\Mocks\Requests;

trait DisputesActions
{
    /**
     * @return array
     */
    protected function acceptDisputeClaimParams(): array
    {
        return $this->jsonDecodeFunction()('{
  "note": "Full refund to the customer.",
  "accept_claim_type": "REFUND"
}', true);
    }

    /**
     * @return array
     */
    protected function acceptDisputeResolutionParams(): array
    {
        return $this->jsonDecodeFunction()('{
  "note": "I am ok with the refund offered."
}', true);
    }

    /**
     * @return array
     */
    protected function acknowledgeItemReturnedParams(): array
    {
        return $this->jsonDecodeFunction()('{
  "note": "I have received the item back.",
  "acknowledgement_type": "ITEM_RECEIVED"
}', true);
    }
}
