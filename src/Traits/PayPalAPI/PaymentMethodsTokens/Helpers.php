<?php

namespace Srmklive\PayPal\Traits\PayPalAPI\PaymentMethodsTokens;

trait Helpers
{
    /**
     * @var array
     */
    protected $payment_source = [];

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
    public function setTokenSource(string $id, string $type): \Srmklive\PayPal\Services\PayPal
    {
        $token_source = [
            'id'    => $id,
            'type'  => $type,
        ];

        return $this->setPaymentSourceDetails('token', $token_source);
    }

    /**
     * Set payment method token customer id.
     *
     * @param string $id
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function setCustomerSource(string $id): \Srmklive\PayPal\Services\PayPal
    {
        $this->customer_source = [
            'id' => $id,
        ];

        return $this;
    }

    /**
     * Set payment source data for credit card.
     *
     * @param array $data
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function setPaymentSourceCard(array $data): \Srmklive\PayPal\Services\PayPal
    {
        return $this->setPaymentSourceDetails('card', $data);
    }

    /**
     * Set payment source data for PayPal.
     *
     * @param array $data
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function setPaymentSourcePayPal(array $data): \Srmklive\PayPal\Services\PayPal
    {
        return $this->setPaymentSourceDetails('paypal', $data);
    }

    /**
     * Set payment source data for Venmo.
     *
     * @param array $data
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    public function setPaymentSourceVenmo(array $data): \Srmklive\PayPal\Services\PayPal
    {
        return $this->setPaymentSourceDetails('venmo', $data);
    }

    /**
     * Set payment source details.
     *
     * @param string $source
     * @param array  $data
     *
     * @return \Srmklive\PayPal\Services\PayPal
     */
    protected function setPaymentSourceDetails(string $source, array $data): \Srmklive\PayPal\Services\PayPal
    {
        $this->payment_source[$source] = $data;

        return $this;
    }

    /**
     * Send request for creating payment method token/source.
     *
     * @param bool $create_source
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     */
    public function sendPaymentMethodRequest(bool $create_source = false)
    {
        $token_payload = ['payment_source' => $this->payment_source];

        if (!empty($this->customer_source)) {
            $token_payload['customer'] = $this->customer_source;
        }

        return ($create_source === true) ? $this->createPaymentSetupToken(array_filter($token_payload)) : $this->createPaymentSourceToken(array_filter($token_payload));
    }
}
