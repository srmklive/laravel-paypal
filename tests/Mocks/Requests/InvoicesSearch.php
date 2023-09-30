<?php

namespace Srmklive\PayPal\Tests\Mocks\Requests;

trait InvoicesSearch
{
    /**
     * @return array
     */
    private function invoiceSearchParams(): array
    {
        return $this->jsonDecodeFunction()('{
            "total_amount_range": {
                "lower_amount": {
                    "currency_code": "USD",
                    "value": "20.00"
                },
                "upper_amount": {
                    "currency_code": "USD",
                    "value": "50.00"
                }
            },
                "invoice_date_range": {
                    "start": "2018-06-01",
                    "end": "2018-06-21"
                }
            }', true);
    }
}
