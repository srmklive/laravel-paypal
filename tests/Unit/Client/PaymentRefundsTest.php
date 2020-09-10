<?php

namespace Srmklive\PayPal\Tests\Unit\Client;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class PaymentRefundsTest extends TestCase
{
    use MockClientClasses;
    use MockResponsePayloads;

    /** @test */
    public function it_can_show_details_for_a_refund()
    {
        $expectedResponse = $this->mockGetRefundDetailsResponse();

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v2/payments/refunds/1JU08902781691411';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(\GuzzleHttp\json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'get');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->get($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
