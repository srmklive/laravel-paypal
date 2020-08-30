<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait Disputes
{
    /**
     * List disputes.
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/customer-disputes/v1/#disputes_list
     */
    public function listDisputes()
    {
        $this->apiEndPoint = 'v1/customer/disputes';
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Update a dispute.
     *
     * @param array  $data
     * @param string $dispute_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/customer-disputes/v1/#disputes_patch
     */
    public function updateDispute(array $data, $dispute_id)
    {
        $this->apiEndPoint = "v1/customer/disputes/{$dispute_id}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->options['json'] = $data;

        $this->verb = 'patch';

        return $this->doPayPalRequest();
    }

    /**
     * Get dispute details.
     *
     * @param string $dispute_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/customer-disputes/v1/#disputes_get
     */
    public function showDisputeDetails($dispute_id)
    {
        $this->apiEndPoint = "v1/customer/disputes/{$dispute_id}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }
}
