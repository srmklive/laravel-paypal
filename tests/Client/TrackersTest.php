<?php

namespace Srmklive\PayPal\Tests\Client;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class TrackersTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_get_tracking_details_for_tracking_id()
    {
        $expectedResponse = [
            'transaction_id'    => '8MC585209K746392H',
            'tracking_number'   => '443844607820',
            'status'            => 'SHIPPED',
            'carrier'           => 'FEDEX',
            'links'             => [
                [
                    'href'  => 'https://api.sandbox.paypal.com/v1/shipping/trackers/8MC585209K746392H-443844607820',
                    'rel'   => 'self',
                ],
                [
                    'href'      => 'https://api.sandbox.paypal.com/v1/shipping/trackers/8MC585209K746392H-443844607820',
                    'rel'       => 'replace',
                    'method'    => 'PUT',
                ],
                [
                    'href'      => 'https://api.sandbox.paypal.com/v1/shipping/trackers-batch',
                    'method'    => 'POST',
                ],
            ],
        ];

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v1/shipping/trackers/8MC585209K746392H-443844607820';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'get');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->get($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_update_tracking_details_for_tracking_id()
    {
        $expectedResponse = '';

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v1/shipping/trackers/8MC585209K746392H-443844607820';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => [
                'transaction_id'    => '8MC585209K746392H',
                'tracking_number'   => '443844607820',
                'status'            => 'SHIPPED',
                'carrier'           => 'FEDEX',
            ],
        ];

        $mockHttpClient = $this->mock_http_request(json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'put');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->put($expectedEndpoint, $expectedParams)->getBody(), true));
    }

    /** @test */
    public function it_can_create_tracking_in_batches()
    {
        $expectedResponse = [
            'tracker_identifiers' => [
                [
                    'transaction_id'    => '8MC585209K746392H',
                    'tracking_number'   => '443844607820',
                    'status'            => 'SHIPPED',
                    'carrier'           => 'FEDEX',
                    'links'             => [
                        [
                            'href'  => 'https://api.sandbox.paypal.com/v1/shipping/trackers/8MC585209K746392H-443844607820',
                            'rel'   => 'self',
                        ],
                        [
                            'href'      => 'https://api.sandbox.paypal.com/v1/shipping/trackers/8MC585209K746392H-443844607820',
                            'rel'       => 'replace',
                            'method'    => 'PUT',
                        ],
                        [
                            'href'      => 'https://api.sandbox.paypal.com/v1/shipping/trackers-batch',
                            'method'    => 'POST',
                        ],
                    ],
                ],
            ],
        ];

        $expectedEndpoint = 'https://api.sandbox.paypal.com/v1/shipping/trackers-batch';
        $expectedParams = [
            'headers' => [
                'Accept'            => 'application/json',
                'Accept-Language'   => 'en_US',
                'Authorization'     => 'Bearer some-token',
            ],
            'json' => [
                'trackers' => [
                    'transaction_id'    => '8MC585209K746392H',
                    'tracking_number'   => '443844607820',
                    'status'            => 'SHIPPED',
                    'carrier'           => 'FEDEX',
                ],
            ],
        ];

        $mockHttpClient = $this->mock_http_request(json_encode($expectedResponse), $expectedEndpoint, $expectedParams, 'post');

        $this->assertEquals($expectedResponse, \GuzzleHttp\json_decode($mockHttpClient->post($expectedEndpoint, $expectedParams)->getBody(), true));
    }
}
