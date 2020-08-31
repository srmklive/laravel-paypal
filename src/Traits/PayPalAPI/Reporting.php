<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

use Carbon\Carbon;

trait Reporting
{
    /**
     * List all transactions.
     *
     * @param int $page
     * @param int $page_size
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/transaction-search/v1/#transactions_get
     */
    public function listTransactions($page = 1, $page_size = 100)
    {
        $this->apiEndPoint = "v1/reporting/transactions?page={$page}&page_size={$page_size}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * List available balance.
     *
     * @param string $date
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/transaction-search/v1/#balances_get
     */
    public function listBalances($date = '')
    {
        $date = empty($date) ? Carbon::now()->toIso8601String() : Carbon::parse($date)->toIso8601String();

        $this->apiEndPoint = "v1/reporting/balances?currency_code={$this->currency}&as_of_date={$date}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }
}
