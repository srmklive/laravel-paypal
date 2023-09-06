<?php

namespace Srmklive\PayPal\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class AdapterPaymentMethodTokensHelpersTest extends TestCase
{
    use MockClientClasses;
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
    public function it_can_create_payment_token_from_a_vault_token()
    {
        $this->client->setAccessToken([
            'access_token'  => self::$access_token,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mock_http_client(
                $this->mockCreatePaymentMethodsTokenResponse()
            )
        );

        $this->client = $this->client->setTokenSource('5C991763VB2781612', 'SETUP_TOKEN')
        ->setCustomerSource('customer_4029352050');

        $response = $this->client->sendTokenRequest();

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('customer', $response);
    }
}
