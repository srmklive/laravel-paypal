<?php

namespace Feature;

namespace Srmklive\PayPal\Tests\Feature;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class AdapterExperienceContextTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @var string */
    protected static $access_token = '';

    /** @var \Srmklive\PayPal\Services\PayPal */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new PayPalClient($this->getApiCredentials());

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockAccessTokenResponse()
            )
        );
        $response = $this->client->getAccessToken();

        self::$access_token = $response['access_token'];

        parent::setUp();
    }

    /** @test */
    public function it_can_set_payment_experience_context_before_performing_api_call()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $start_date = Carbon::now()->addDay()->toDateString();

        $this->client = $this->client->setReturnAndCancelUrl('https://example.com/paypal-success', 'https://example.com/paypal-cancel')
            ->setBrandName('Test Brand')
            ->addProductById('PROD-XYAB12ABSB7868434')
            ->addBillingPlanById('P-5ML4271244454362WXNWU5NQ');

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreateSubscriptionResponse()
            )
        );

        $response = $this->client->setupSubscription('John Doe', 'john@example.com', $start_date);

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('plan_id', $response);
    }
}
