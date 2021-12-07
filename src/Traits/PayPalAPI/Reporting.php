<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

use Carbon\Carbon;

trait Reporting
{
    /**
     * List all transactions.
     *
     * @param array  $filters
     * @param string $fields
     * @param int    $page
     * @param int    $page_size
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/transaction-search/v1/#transactions_get
     */
    public function listTransactions(array $filters, string $fields = 'all', int $page = 1, int $page_size = 100)
    {
        $filters_list = collect($filters)->isEmpty() ? '' :
            collect($filters)->map(function ($value, $key) {
                return "{$key}={$value}&";
            })->implode('');

        $this->apiEndPoint = "v1/reporting/transactions?{$filters_list}fields={$fields}&page={$page}&page_size={$page_size}";

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * List available balance.
     *
     * @param string $date
     * @param string $balance_currency
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/transaction-search/v1/#balances_get
     */
    public function listBalances(string $date = '', string $balance_currency = '')
    {
        $date = empty($date) ? Carbon::now()->toIso8601String() : Carbon::parse($date)->toIso8601String();
        $currency = empty($balance_currency) ? $this->getCurrency() : $balance_currency;

        $this->apiEndPoint = "v1/reporting/balances?currency_code={$currency}&as_of_date={$date}";

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }
}
