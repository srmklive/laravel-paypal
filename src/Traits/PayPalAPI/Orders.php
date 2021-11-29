<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait Orders
{
    /**
     * Creates an order.
     *
     * @param array $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/orders/v2/#orders_create
     */
    public function createOrder(array $data)
    {
        $this->apiEndPoint = 'v2/checkout/orders';

        $this->options['json'] = (object) $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    public function updateOrder(string $order_id, array $data)
    {
        $this->apiEndPoint = "v2/checkout/orders/{$order_id}";

        $this->options['json'] = (object) $data;

        $this->verb = 'patch';

        return $this->doPayPalRequest();
    }

    /**
     * Shows details for an order.
     *
     * @param string $order_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/orders/v2/#orders_get
     */
    public function showOrderDetails(string $order_id)
    {
        $this->apiEndPoint = "v2/checkout/orders/{$order_id}";

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Authorizes payment for an order.
     *
     * @param string $order_id
     * @param array  $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/orders/v2/#orders_authorize
     */
    public function authorizePaymentOrder(string $order_id, array $data = [])
    {
        $this->apiEndPoint = "v2/checkout/orders/{$order_id}/authorize";

        $this->options['json'] = (object) $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Captures payment for an order.
     *
     * @param string $order_id
     * @param array  $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/orders/v2/#orders_capture
     */
    public function capturePaymentOrder(string $order_id, array $data = [])
    {
        $this->apiEndPoint = "v2/checkout/orders/{$order_id}/capture";

        $this->options['json'] = (object) $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
