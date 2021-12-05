<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class BillingPlansTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_create_a_billing_plan()
    {
        $expectedResponse = $this->mockCreatePlansResponse();

        $expectedParams = $this->createPlanParams();

        $expectedMethod = 'createPlan';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, 'some-request-id'));
    }

    /** @test */
    public function it_can_list_billing_plans()
    {
        $expectedResponse = $this->mockListPlansResponse();

        $expectedMethod = 'listPlans';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}(1, 2, true));
    }

    /** @test */
    public function it_can_update_a_billing_plan()
    {
        $expectedResponse = '';

        $expectedParams = $this->updatePlanParams();

        $expectedMethod = 'updatePlan';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('P-7GL4271244454362WXNWU5NQ', $expectedParams));
    }

    /** @test */
    public function it_can_show_details_for_a_billing_plan()
    {
        $expectedResponse = $this->mockGetPlansResponse();

        $expectedMethod = 'showPlanDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('P-7GL4271244454362WXNWU5NQ'));
    }

    /** @test */
    public function it_can_activate_a_billing_plan()
    {
        $expectedResponse = '';

        $expectedMethod = 'activatePlan';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('P-7GL4271244454362WXNWU5NQ'));
    }

    /** @test */
    public function it_can_deactivate_a_billing_plan()
    {
        $expectedResponse = '';

        $expectedMethod = 'deactivatePlan';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('P-7GL4271244454362WXNWU5NQ'));
    }

    /** @test */
    public function it_can_update_pricing_for_a_billing_plan()
    {
        $expectedResponse = '';

        $expectedParams = $this->updatePlanPricingParams();

        $expectedMethod = 'updatePlanPricing';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('P-2UF78835G6983425GLSM44MA', $expectedParams));
    }
}
