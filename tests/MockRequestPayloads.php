<?php

namespace Srmklive\PayPal\Tests;

trait MockRequestPayloads
{
    use Mocks\Requests\BillingPlans;
    use Mocks\Requests\CatalogProducts;
    use Mocks\Requests\Disputes;
    use Mocks\Requests\DisputesActions;
    use Mocks\Requests\Invoices;
    use Mocks\Requests\InvoicesSearch;
    use Mocks\Requests\InvoicesTemplates;
    use Mocks\Requests\Orders;
    use Mocks\Requests\PaymentAuthorizations;
    use Mocks\Requests\PaymentCaptures;
    use Mocks\Requests\Subscriptions;
    use Mocks\Requests\Trackers;
    use Mocks\Requests\WebHooks;
}
