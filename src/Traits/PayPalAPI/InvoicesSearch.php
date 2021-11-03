<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

use Srmklive\PayPal\Traits\PayPalAPI\InvoiceSearch\Filters;

trait InvoicesSearch
{
    use Filters;

    /**
     * Search and return existing invoices.
     *
     * @param int $page
     * @param int   $size
     * @param bool $totals
     * @param array $fields
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @throws \Throwable
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_list
     */
    public function searchInvoices(int $page = 1, int $size = 20, bool $totals = true, array $fields = [])
    {
        $totals = ($totals === true) ? 'true' : 'false';

        if (collect($this->invoice_search_filters)->count() < 1) {
            $this->invoice_search_filters = [
                'currency_code' => $this->getCurrency()
            ];
        }

        $fields_list = collect($fields);

        $fields = ($fields_list->count() > 0) ? "&fields={$fields_list->implode(',')}" : '';

        $this->apiEndPoint = "v2/invoicing/search-invoices?page={$page}&page_size={$size}&total_required={$totals}{$fields}";
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->options['json'] = $this->invoice_search_filters;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
