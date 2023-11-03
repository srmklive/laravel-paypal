<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait MerchantIntegrations
{
    /**
     * Shows seller status.
     *
     * @param string $partner_id
     * @param string $merchant_id
     *
     * @return mixed
     */
    public function showSellerStatus(string $partner_id, string $merchant_id)
    {
        $this->apiEndPoint = "v1/customer/partners/{$partner_id}/merchant-integrations/{$merchant_id}";

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }
}
