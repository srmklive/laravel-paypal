<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class ReportingTest extends TestCase
{
    use MockClientClasses;
    use MockResponsePayloads;

    /** @test */
    public function it_can_list_transactions()
    {
        $expectedResponse = $this->mockListTransactionsResponse();

        $expectedMethod = 'listTransactions';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $filters = [
            'start_date'    => Carbon::now()->toIso8601String(),
            'end_date'      => Carbon::now()->subDays(30)->toIso8601String(),
        ];

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($filters));
    }

    /** @test */
    public function it_can_list_balances()
    {
        $expectedResponse = $this->mockListBalancesResponse();

        $expectedMethod = 'listBalances';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('2016-10-15T06:07:00-0700'));
    }
}
