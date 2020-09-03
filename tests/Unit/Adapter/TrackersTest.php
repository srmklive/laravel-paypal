<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

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

        $expectedParams = '8MC585209K746392H-443844607820';

        $expectedMethod = 'showTrackingDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }

    /** @test */
    public function it_can_update_tracking_details_for_tracking_id()
    {
        $expectedResponse = '';

        $expectedData = [
            'transaction_id'    => '8MC585209K746392H',
            'tracking_number'   => '443844607820',
            'status'            => 'SHIPPED',
            'carrier'           => 'FEDEX',
        ];

        $expectedParams = '8MC585209K746392H-443844607820';

        $expectedMethod = 'updateTrackingDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams, $expectedData));
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

        $expectedParams = [
            'trackers' => [
                'transaction_id'    => '8MC585209K746392H',
                'tracking_number'   => '443844607820',
                'status'            => 'SHIPPED',
                'carrier'           => 'FEDEX',
            ],
        ];

        $expectedMethod = 'addBatchTracking';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }
}
