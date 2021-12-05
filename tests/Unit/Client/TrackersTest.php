<?php

namespace Srmklive\PayPal\Tests\Unit\Client;

use GuzzleHttp\Utils;
use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;
use Srmklive\PayPal\Tests\MockRequestPayloads;
use Srmklive\PayPal\Tests\MockResponsePayloads;

class TrackersTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    /** @test */
    public function it_can_get_tracking_details_for_tracking_id()
    {
        $expectedResponse = $this->mockGetTrackingDetailsResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/shipping/trackers/8MC585209K746392H-443844607820';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'get');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->get($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_update_tracking_details_for_tracking_id()
    {
        $expectedResponse = '';

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/shipping/trackers/8MC585209K746392H-443844607820';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => $this->mockUpdateTrackingDetailsParams(),
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'put');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->put($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_create_tracking_in_batches()
    {
        $expectedResponse = $this->mockCreateTrackinginBatchesResponse();

        $expectedEndpoint = 'https://api-m.sandbox.paypal.com/v1/shipping/trackers-batch';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => $this->mockCreateTrackinginBatchesParams(),
        ];

        $mockHttpClient = $this->mock_http_request(Utils::jsonEncode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, Utils::jsonDecode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
