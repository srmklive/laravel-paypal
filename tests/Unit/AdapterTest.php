<?php

namespace Srmklive\PayPal\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class AdapterTest extends TestCase
{
    use MockClientClasses;
    use MockResponsePayloads;

    /** @test */
    public function it_can_be_instantiated()
    {
        $client = new PayPalClient();

        $this->assertInstanceOf(PayPalClient::class, $client);
    }

    /** @test */
    public function it_can_get_access_token()
    {
        $expectedResponse = $this->mockAccessTokenResponse();

        $expectedMethod = 'getAccessToken';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, false);

        $mockClient->setApiCredentials($this->getMockCredentials());

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }
}
