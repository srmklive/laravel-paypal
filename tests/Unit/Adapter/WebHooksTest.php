<?php

namespace Srmklive\PayPal\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class WebHooksTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_create_a_web_hook()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "0EH40505U7160970P",
  "url": "https://example.com/example_webhook",
  "event_types": [
    {
      "name": "PAYMENT.AUTHORIZATION.CREATED",
      "description": "A payment authorization was created."
    },
    {
      "name": "PAYMENT.AUTHORIZATION.VOIDED",
      "description": "A payment authorization was voided."
    }
  ],
  "links": [
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
      "rel": "update",
      "method": "PATCH"
    },
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
      "rel": "delete",
      "method": "DELETE"
    }
  ]
}', true);

        $expectedMethod = 'createWebHook';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}(
            'https://example.com/example_webhook',
            ['PAYMENT.AUTHORIZATION.CREATED', 'PAYMENT.AUTHORIZATION.VOIDED']
        ));
    }

    /** @test */
    public function it_can_list_web_hooks()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "webhooks": [
    {
      "id": "40Y916089Y8324740",
      "url": "https://example.com/example_webhook",
      "event_types": [
        {
          "name": "PAYMENT.AUTHORIZATION.CREATED",
          "description": "A payment authorization was created."
        },
        {
          "name": "PAYMENT.AUTHORIZATION.VOIDED",
          "description": "A payment authorization was voided."
        }
      ],
      "links": [
        {
          "href": "https://api.paypal.com/v1/notifications/webhooks/40Y916089Y8324740",
          "rel": "self",
          "method": "GET"
        },
        {
          "href": "https://api.paypal.com/v1/notifications/webhooks/40Y916089Y8324740",
          "rel": "update",
          "method": "PATCH"
        },
        {
          "href": "https://api.paypal.com/v1/notifications/webhooks/40Y916089Y8324740",
          "rel": "delete",
          "method": "DELETE"
        }
      ]
    },
    {
      "id": "0EH40505U7160970P",
      "url": "https://example.com/another_example_webhook",
      "event_types": [
        {
          "name": "PAYMENT.AUTHORIZATION.CREATED",
          "description": "A payment authorization was created."
        },
        {
          "name": "PAYMENT.AUTHORIZATION.VOIDED",
          "description": "A payment authorization was voided."
        }
      ],
      "links": [
        {
          "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
          "rel": "self",
          "method": "GET"
        },
        {
          "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
          "rel": "update",
          "method": "PATCH"
        },
        {
          "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
          "rel": "delete",
          "method": "DELETE"
        }
      ]
    }
  ]
}', true);

        $expectedMethod = 'listWebHooks';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_delete_a_web_hook()
    {
        $expectedResponse = '';

        $expectedMethod = 'deleteWebHook';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('5GP028458E2496506'));
    }

    /** @test */
    public function it_can_update_a_web_hook()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "0EH40505U7160970P",
  "url": "https://example.com/example_webhook_2",
  "event_types": [
    {
      "name": "PAYMENT.SALE.REFUNDED",
      "description": "A sale payment was refunded."
    }
  ],
  "links": [
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
      "rel": "update",
      "method": "PATCH"
    },
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
      "rel": "delete",
      "method": "DELETE"
    }
  ]
}', true);

        $expectedParams = \GuzzleHttp\json_decode('[
  {
    "op": "replace",
    "path": "/url",
    "value": "https://example.com/example_webhook_2"
  },
  {
    "op": "replace",
    "path": "/event_types",
    "value": [
      {
        "name": "PAYMENT.SALE.REFUNDED"
      }
    ]
  }
]', true);

        $expectedMethod = 'updateWebHook';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('0EH40505U7160970P', $expectedParams));
    }

    /** @test */
    public function it_can_show_details_for_a_web_hook()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "0EH40505U7160970P",
  "url": "https://example.com/example_webhook",
  "event_types": [
    {
      "name": "PAYMENT.AUTHORIZATION.CREATED",
      "description": "A payment authorization was created.",
      "status": "ENABLED"
    },
    {
      "name": "PAYMENT.AUTHORIZATION.VOIDED",
      "description": "A payment authorization was voided.",
      "status": "ENABLED"
    }
  ],
  "links": [
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
      "rel": "update",
      "method": "PATCH"
    },
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks/0EH40505U7160970P",
      "rel": "delete",
      "method": "DELETE"
    }
  ]
}', true);

        $expectedMethod = 'showWebHookDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('0EH40505U7160970P'));
    }

    /** @test */
    public function it_can_list_web_hooks_events()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "event_types": [
    {
      "name": "PAYMENT.AUTHORIZATION.CREATED",
      "description": "A payment authorization was created.",
      "status": "ENABLED"
    },
    {
      "name": "PAYMENT.AUTHORIZATION.VOIDED",
      "description": "A payment authorization was voided.",
      "status": "ENABLED"
    },
    {
      "name": "RISK.DISPUTE.CREATED",
      "description": "A dispute was filed against a transaction.",
      "status": "DEPRECATED"
    }
  ]
}', true);

        $expectedMethod = 'listWebHookEvents';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getMockCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('0EH40505U7160970P'));
    }
}
