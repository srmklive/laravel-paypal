<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class PaymentExperienceWebProfilesTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_list_web_experience_profiles()
    {
        $expectedResponse = $this->mockListWebProfilesResponse();

        $expectedMethod = 'listWebExperienceProfiles';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_create_web_experience_profile()
    {
        $expectedResponse = $this->mockWebProfileResponse();

        $expectedParams = $this->mockCreateWebProfileParams();

        $expectedMethod = 'createWebExperienceProfile';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, 'some-request-id'));
    }

    /** @test */
    public function it_can_delete_web_experience_profile()
    {
        $expectedResponse = '';

        $expectedParams = 'XP-A88A-LYLW-8Y3X-E5ER';

        $expectedMethod = 'deleteWebExperienceProfile';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }

    /** @test */
    public function it_can_partially_update_web_experience_profile()
    {
        $expectedResponse = '';

        $expectedParams = $this->partiallyUpdateWebProfileParams();

        $expectedMethod = 'patchWebExperienceProfile';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('XP-A88A-LYLW-8Y3X-E5ER', $expectedParams));
    }

    /** @test */
    public function it_can_fully_update_web_experience_profile()
    {
        $expectedResponse = '';

        $expectedParams = $this->updateWebProfileParams();

        $expectedMethod = 'updateWebExperienceProfile';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('XP-A88A-LYLW-8Y3X-E5ER', $expectedParams));
    }

    /** @test */
    public function it_can_get_web_experience_profile_details()
    {
        $expectedResponse = $this->mockWebProfileResponse();

        $expectedParams = 'XP-A88A-LYLW-8Y3X-E5ER';

        $expectedMethod = 'showWebExperienceProfileDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }
}
