<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait Payouts
{
    /**
     * Create a Batch Payout.
     *
     * @param array $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/payments.payouts-batch/v1/#payouts_post
     */
    public function createBatchPayout(array $data)
    {
        $this->apiEndPoint = 'v1/payments/payouts';

        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Show Batch Payout details by ID.
     *
     * @param string $payout_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/payments.payouts-batch/v1/#payouts_get
     */
    public function showBatchPayoutDetails(string $payout_id)
    {
        $this->apiEndPoint = "v1/payments/payouts/{$payout_id}";

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Show Payout Item details by ID.
     *
     * @param string $payout_item_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/payments.payouts-batch/v1/#payouts-item_get
     */
    public function showPayoutItemDetails(string $payout_item_id)
    {
        $this->apiEndPoint = "v1/payments/payouts-item/{$payout_item_id}";

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Show Payout Item details by ID.
     *
     * @param string $payout_item_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/payments.payouts-batch/v1/#payouts-item_cancel
     */
    public function cancelUnclaimedPayoutItem(string $payout_item_id)
    {
        $this->apiEndPoint = "v1/payments/payouts-item/{$payout_item_id}/cancel";

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
