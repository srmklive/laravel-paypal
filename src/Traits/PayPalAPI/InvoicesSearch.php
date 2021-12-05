<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

use Srmklive\PayPal\Traits\PayPalAPI\InvoiceSearch\Filters;

trait InvoicesSearch
{
    use Filters;

    /**
     * Search and return existing invoices.
     *
     * @param int  $page
     * @param int  $size
     * @param bool $totals
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_list
     */
    public function searchInvoices(int $page = 1, int $size = 20, bool $totals = true)
    {
        $totals = ($totals === true) ? 'true' : 'false';

        if (collect($this->invoice_search_filters)->count() < 1) {
            $this->invoice_search_filters = [
                'currency_code' => $this->getCurrency(),
            ];
        }

        $this->apiEndPoint = "v2/invoicing/search-invoices?page={$page}&page_size={$size}&total_required={$totals}";

        $this->options['json'] = $this->invoice_search_filters;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
