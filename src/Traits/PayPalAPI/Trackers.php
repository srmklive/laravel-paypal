<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait Trackers
{
    /**
     * Show tracking information.
     *
     * @param string $tracking_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/tracking/v1/#trackers_get
     */
    public function showTrackingDetails($tracking_id)
    {
        $this->apiEndPoint = "v1/shipping/trackers/{$tracking_id}";
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Update tracking information.
     *
     * @param string $tracking_id
     * @param array  $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/tracking/v1/#trackers_put
     */
    public function updateTrackingDetails($tracking_id, $data)
    {
        $this->apiEndPoint = "v1/shipping/trackers/{$tracking_id}";
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->options['json'] = $data;

        $this->verb = 'put';

        return $this->doPayPalRequest(false);
    }

    /**
     * Add batch tracking.
     *
     * @param array $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/tracking/v1/#trackers-batch_post
     */
    public function addBatchTracking($data)
    {
        $this->apiEndPoint = 'v1/shipping/trackers-batch';
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
