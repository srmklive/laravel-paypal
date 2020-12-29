<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class PaymentAuthorizationsTest extends TestCase
{
    use MockClientClasses;
    use MockResponsePayloads;

    /** @test */
    public function it_can_show_details_for_an_authorized_payment()
    {
        $expectedResponse = $this->mockGetAuthorizedPaymentDetailsResponse();

        $expectedMethod = 'showAuthorizedPaymentDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('0VF52814937998046'));
    }

    /** @test */
    public function it_can_capture_an_authorized_payment()
    {
        $expectedResponse = $this->mockCaptureAuthorizedPaymentResponse();

        $expectedMethod = 'captureAuthorizedPayment';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}(
            '0VF52814937998046',
            'INVOICE-123',
            10.99,
            'Payment is due'
        ));
    }

    /** @test */
    public function it_can_reauthorize_an_authorized_payment()
    {
        $expectedResponse = $this->mockReAuthorizeAuthorizedPaymentResponse();

        $expectedMethod = 'reAuthorizeAuthorizedPayment';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('0VF52814937998046', 10.99));
    }

    /** @test */
    public function it_can_void_an_authorized_payment()
    {
        $expectedResponse = '';

        $expectedMethod = 'voidAuthorizedPayment';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, true);

        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('0VF52814937998046'));
    }
}
