<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait BillingAgreements {

    /**
     * Creates a billing agreement token.
     *
     * @param array $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/limited-release/reference-transactions/#link-createbillingagreementtoken
     */
    public function createBillingAgreementToken (array $data) {
        $this->apiEndPoint = 'v1/billing-agreements/agreement-tokens';

        $this->options['json'] = (object) $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * After payer approval, use a billing agreement token to create the agreement.
     *
     * @param string $billingAgreementToken
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/limited-release/reference-transactions/#link-createbillingagreement
     */
    public function createBillingAgreement (string $billingAgreementToken) {
        $this->apiEndPoint = "v1/billing-agreements/{$billingAgreementToken}/agreements";

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
