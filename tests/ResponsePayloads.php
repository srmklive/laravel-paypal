<?php

namespace Srmklive\PayPal\Tests;

trait ResponsePayloads
{
    use Mocks\Responses\BillingPlans;
    use Mocks\Responses\CatalogProducts;
    use Mocks\Responses\Disputes;
    use Mocks\Responses\DisputesActions;
    use Mocks\Responses\Invoices;
    use Mocks\Responses\InvoicesSearch;
    use Mocks\Responses\Reporting;

    /**
     * @return array
     */
    private function mockAccessTokenResponse()
    {
        return [
            'scope'         => 'some_scope',
            'access_token'  => 'some_access_token',
            'token_type'    => 'Bearer',
            'app_id'        => 'APP-80W284485P519543T',
            'expires_in'    => 32400,
            'nonce'         => 'some_nonce',
        ];
    }
}
