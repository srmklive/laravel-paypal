<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait InvoicesSearch
{
    /**
     * Search and return existing invoices.
     *
     * @param array $filters
     * @param int   $page
     * @param int   $size
     * @param bool  $totals
     * @param array $fields
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_list
     */
    public function searchInvoices($filters, $page = 1, $size = 20, $totals = true, array $fields = [])
    {
        $totals = ($totals === true) ? 'true' : 'false';

        $fields_list = collect($fields);

        $fields = ($fields_list->count() > 0) ? "&fields={$fields_list->implode(',')}" : '';

        $this->apiEndPoint = "v2/invoicing/search-invoices?page={$page}&page_size={$size}&total_required={$totals}{$fields}";
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->options['json'] = $filters;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
