<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class IdentityTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_get_user_profile_details()
    {
        $expectedResponse = $this->mockShowProfileInfoResponse();

        $expectedMethod = 'showProfileInfo';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_create_merchant_applications()
    {
        $expectedResponse = $this->mockCreateMerchantApplicationResponse();

        $expectedMethod = 'createMerchantApplication';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}(
            'AGGREGATOR',
            [
                'https://example.com/callback',
                'https://example.com/callback2',
            ],
            [
                'facilitator@example.com',
                'merchant@example.com',
            ],
            'WDJJHEBZ4X2LY',
            'some-open-id'
        ));
    }

    /** @test */
    public function it_can_set_account_properties()
    {
        $expectedResponse = '';

        $expectedParams = $this->mockSetAccountPropertiesParams();

        $expectedMethod = 'setAccountProperties';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }

    /** @test */
    public function it_can_disable_account_properties()
    {
        $expectedResponse = '';

        $expectedMethod = 'disableAccountProperties';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }
}
