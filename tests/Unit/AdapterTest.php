<?php

namespace Srmklive\PayPal\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;

class AdapterTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_be_instantiated()
    {
        $client = new PayPalClient();

        $this->assertInstanceOf(PayPalClient::class, $client);
    }

    /** @test */
    public function it_can_get_access_token()
    {
        $expectedResponse = [
            'scope'         => 'some_scope',
            'access_token'  => 'some_access_token',
            'token_type'    => 'Bearer',
            'app_id'        => 'APP-80W284485P519543T',
            'expires_in'    => 32400,
            'nonce'         => 'some_nonce',
        ];

        $expectedMethod = 'getAccessToken';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials());

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }
}
