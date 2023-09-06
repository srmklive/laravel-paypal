<?php

namespace Srmklive\PayPal\Traits\PayPalAPI\PaymentMethodsTokens;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Throwable;

trait Helpers
{
    /**
     * @var array
     */
    protected $token_source = [];

    /**
     * @var array
     */
    protected $customer_source = [];

    /**
     * Set payment method token by token id.
     *
     * @param string $id
     * @param string $type
     *
     * @return \Srmklive\PayPal\Services\PayPal  
     */
    public function setTokenSource(string $id, string $type)
    {
        $this->token_source = [
            'id'    => $id,
            'type'  => $type,
        ];

        return $this;
    }

    /**
     * Set payment method token customer id.
     *
     * @param string $id
     *
     * @return \Srmklive\PayPal\Services\PayPal  
     */
    public function setCustomerSource(string $id)
    {
        $this->customer_source = [
            'id' => $id,
        ];

        return $this;
    }    

    /**
     * Send request for creating payment method token.
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     */
    public function sendTokenRequest()
    {
        $token_payload = ['payment_source' => null];

        if (!empty($this->token_source)) {
            $token_payload['payment_source']['token'] = $this->token_source;
        }     

        if (!empty($this->customer_source)) {
            $token_payload['customer'] = $this->customer_source;
        }

        return $this->createPaymentSourceToken(array_filter($token_payload));
    }
}
