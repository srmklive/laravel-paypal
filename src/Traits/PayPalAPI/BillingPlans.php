<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait BillingPlans
{
    /**
     * Create a new billing plan.
     *
     * @param array $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/subscriptions/v1/#plans_create
     */
    public function createPlan(array $data)
    {
        $this->apiEndPoint = 'v1/billing/plans';
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * List all billing plans.
     *
     * @param int   $page
     * @param int   $size
     * @param bool  $totals
     * @param array $fields
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/subscriptions/v1/#plans_list
     */
    public function listPlans($page = 1, $size = 20, $totals = true, array $fields = [])
    {
        $fields_list = collect($fields);

        $fields = ($fields_list->count() > 0) ? "&fields={$fields_list->implode(',')}" : '';

        $this->apiEndPoint = "v1/billing/plans?page={$page}&page_size={$size}&total_required={$totals}{$fields}";
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Update an existing billing plan.
     *
     * @param string $plan_id
     * @param array  $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_update
     */
    public function updatePlan($plan_id, array $data)
    {
        $this->apiEndPoint = "v1/billing/plans/{$plan_id}";
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->options['json'] = $data;

        $this->verb = 'patch';

        return $this->doPayPalRequest(false);
    }

    /**
     * Show details for an existing billing plan.
     *
     * @param string $plan_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/subscriptions/v1/#plans_get
     */
    public function showPlanDetails($plan_id)
    {
        $this->apiEndPoint = "v1/billing/plans/{$plan_id}";
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Activate an existing billing plan.
     *
     * @param string $plan_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/subscriptions/v1/#plans_activate
     */
    public function activatePlan($plan_id)
    {
        $this->apiEndPoint = "v1/billing/plans/{$plan_id}/activate";
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->verb = 'post';

        return $this->doPayPalRequest(false);
    }

    /**
     * Deactivate an existing billing plan.
     *
     * @param string $plan_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/subscriptions/v1/#plans_deactivate
     */
    public function deactivatePlan($plan_id)
    {
        $this->apiEndPoint = "v1/billing/plans/{$plan_id}/deactivate";
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->verb = 'post';

        return $this->doPayPalRequest(false);
    }

    /**
     * Update pricing for an existing billing plan.
     *
     * @param string $plan_id
     * @param array  $pricing
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/subscriptions/v1/#plans_update-pricing-schemes
     */
    public function updatePlanPricing($plan_id, array $pricing)
    {
        $this->apiEndPoint = "v1/billing/plans/{$plan_id}/update-pricing-schemes";
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->options['json'] = [
            'pricing_schemes' => $pricing,
        ];

        $this->verb = 'post';

        return $this->doPayPalRequest(false);
    }
}
