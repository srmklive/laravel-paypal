<?php

namespace Srmklive\PayPal\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Tests\MockClientClasses;

class WebHooksEventsTest extends TestCase
{
    use MockClientClasses;

    /** @test */
    public function it_can_list_web_hooks_event_types()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "event_types": [
    {
      "name": "PAYMENT.AUTHORIZATION.CREATED",
      "description": "A payment authorization was created.",
      "status": "ENABLED",
      "service": "amqpaymentwebhookd",
      "owner": "Webhooks",
      "contact": "livesupport@example.com"
    },
    {
      "name": "PAYMENT.AUTHORIZATION.VOIDED",
      "description": "A payment authorization was voided.",
      "status": "ENABLED",
      "service": "amqpaymentwebhookd",
      "owner": "Webhooks",
      "contact": "livesupport@example.com"
    },
    {
      "name": "PAYMENT.CAPTURE.COMPLETED",
      "description": "A capture payment was completed.",
      "status": "ENABLED",
      "service": "amqpaymentwebhookd",
      "owner": "Webhooks",
      "contact": "livesupport@example.com"
    }
  ]
}', true);

        $expectedMethod = 'listEventTypes';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_list_web_hooks_events()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "events": [
    {
      "id": "8PT597110X687430LKGECATA",
      "create_time": "2013-06-25T21:41:28Z",
      "resource_type": "authorization",
      "event_version": "1.0",
      "event_type": "PAYMENT.AUTHORIZATION.CREATED",
      "summary": "A payment authorization was created",
      "resource_version": "1.0",
      "resource": {
        "id": "2DC87612EK520411B",
        "create_time": "2013-06-25T21:39:15Z",
        "update_time": "2013-06-25T21:39:17Z",
        "state": "authorized",
        "amount": {
          "total": "7.47",
          "currency": "USD",
          "details": {
            "subtotal": "7.47"
          }
        },
        "parent_payment": "PAY-36246664YD343335CKHFA4AY",
        "valid_until": "2013-07-24T21:39:15Z",
        "links": [
          {
            "href": "https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B",
            "rel": "self",
            "method": "GET"
          },
          {
            "href": "https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B/capture",
            "rel": "capture",
            "method": "POST"
          },
          {
            "href": "https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B/void",
            "rel": "void",
            "method": "POST"
          },
          {
            "href": "https://api.paypal.com/v1/payments/payment/PAY-36246664YD343335CKHFA4AY",
            "rel": "parent_payment",
            "method": "GET"
          }
        ]
      },
      "links": [
        {
          "href": "https://api.paypal.com/v1/notfications/webhooks-events/8PT597110X687430LKGECATA",
          "rel": "self",
          "method": "GET"
        },
        {
          "href": "https://api.paypal.com/v1/notfications/webhooks-events/8PT597110X687430LKGECATA/resend",
          "rel": "resend",
          "method": "POST"
        }
      ]
    },
    {
      "id": "HTSPGS710X687430LKGECATA",
      "create_time": "2013-06-25T21:41:28Z",
      "resource_type": "authorization",
      "event_version": "1.0",
      "event_type": "PAYMENT.AUTHORIZATION.CREATED",
      "summary": "A payment authorization was created",
      "resource_version": "1.0",
      "resource": {
        "id": "HATH7S72EK520411B",
        "create_time": "2013-06-25T21:39:15Z",
        "update_time": "2013-06-25T21:39:17Z",
        "state": "authorized",
        "amount": {
          "total": "7.47",
          "currency": "USD",
          "details": {
            "subtotal": "7.47"
          }
        },
        "parent_payment": "PAY-ALDSFJ64YD343335CKHFA4AY",
        "valid_until": "2013-07-24T21:39:15Z",
        "links": [
          {
            "href": "https://api.paypal.com/v1/payments/authorization/HATH7S72EK520411B",
            "rel": "self",
            "method": "GET"
          },
          {
            "href": "https://api.paypal.com/v1/payments/authorization/HATH7S72EK520411B/capture",
            "rel": "capture",
            "method": "POST"
          },
          {
            "href": "https://api.paypal.com/v1/payments/authorization/HATH7S72EK520411B/void",
            "rel": "void",
            "method": "POST"
          },
          {
            "href": "https://api.paypal.com/v1/payments/payment/PAY-HATH7S72EK520411B",
            "rel": "parent_payment",
            "method": "GET"
          }
        ]
      },
      "links": [
        {
          "href": "https://api.paypal.com/v1/notfications/webhooks-events/HTSPGS710X687430LKGECATA",
          "rel": "self",
          "method": "GET"
        },
        {
          "href": "https://api.paypal.com/v1/notfications/webhooks-events/HTSPGS710X687430LKGECATA/resend",
          "rel": "resend",
          "method": "POST"
        }
      ]
    }
  ],
  "count": 2,
  "links": [
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks-events/?start_time=2014-08-04T12:46:47-07:00&amp;end_time=2014-09-18T12:46:47-07:00&amp;page_size=2&amp;move_to=next&amp;index_time=2014-09-17T23:07:35Z&amp;index_id=3",
      "rel": "next",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/notifications/webhooks-events/?start_time=2014-08-04T12:46:47-07:00&amp;end_time=2014-09-18T12:46:47-07:00&amp;page_size=2&amp;move_to=previous&amp;index_time=2014-09-17T23:07:35Z&amp;index_id=0",
      "rel": "previous",
      "method": "GET"
    }
  ]
}', true);

        $expectedMethod = 'listEvents';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}());
    }

    /** @test */
    public function it_can_show_details_for_a_web_hooks_event()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "8PT597110X687430LKGECATA",
  "create_time": "2013-06-25T21:41:28Z",
  "resource_type": "authorization",
  "event_version": "1.0",
  "event_type": "PAYMENT.AUTHORIZATION.CREATED",
  "summary": "A payment authorization was created",
  "resource_version": "1.0",
  "resource": {
    "id": "2DC87612EK520411B",
    "create_time": "2013-06-25T21:39:15Z",
    "update_time": "2013-06-25T21:39:17Z",
    "state": "authorized",
    "amount": {
      "total": "7.47",
      "currency": "USD",
      "details": {
        "subtotal": "7.47"
      }
    },
    "parent_payment": "PAY-36246664YD343335CKHFA4AY",
    "valid_until": "2013-07-24T21:39:15Z",
    "links": [
      {
        "href": "https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B",
        "rel": "self",
        "method": "GET"
      },
      {
        "href": "https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B/capture",
        "rel": "capture",
        "method": "POST"
      },
      {
        "href": "https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B/void",
        "rel": "void",
        "method": "POST"
      },
      {
        "href": "https://api.paypal.com/v1/payments/payment/PAY-36246664YD343335CKHFA4AY",
        "rel": "parent_payment",
        "method": "GET"
      }
    ]
  },
  "links": [
    {
      "href": "https://api.paypal.com/v1/notfications/webhooks-events/8PT597110X687430LKGECATA",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/notfications/webhooks-events/8PT597110X687430LKGECATA/resend",
      "rel": "resend",
      "method": "POST"
    }
  ]
}', true);

        $expectedMethod = 'showEventDetails';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('8PT597110X687430LKGECATA'));
    }

    /** @test */
    public function it_can_resend_notification_for_a_web_hooks_event()
    {
        $expectedResponse = \GuzzleHttp\json_decode('{
  "id": "8PT597110X687430LKGECATA",
  "create_time": "2013-06-25T21:41:28Z",
  "resource_type": "authorization",
  "event_version": "1.0",
  "event_type": "PAYMENT.AUTHORIZATION.CREATED",
  "summary": "A payment authorization was created",
  "resource_version": "1.0",
  "resource": {
    "id": "2DC87612EK520411B",
    "create_time": "2013-06-25T21:39:15Z",
    "update_time": "2013-06-25T21:39:17Z",
    "state": "authorized",
    "amount": {
      "total": "7.47",
      "currency": "USD",
      "details": {
        "subtotal": "7.47"
      }
    },
    "parent_payment": "PAY-36246664YD343335CKHFA4AY",
    "valid_until": "2013-07-24T21:39:15Z",
    "links": [
      {
        "href": "https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B",
        "rel": "self",
        "method": "GET"
      },
      {
        "href": "https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B/capture",
        "rel": "capture",
        "method": "POST"
      },
      {
        "href": "https://api.paypal.com/v1/payments/authorization/2DC87612EK520411B/void",
        "rel": "void",
        "method": "POST"
      },
      {
        "href": "https://api.paypal.com/v1/payments/payment/PAY-36246664YD343335CKHFA4AY",
        "rel": "parent_payment",
        "method": "GET"
      }
    ]
  },
  "links": [
    {
      "href": "https://api.paypal.com/v1/notfications/webhooks-events/8PT597110X687430LKGECATA",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api.paypal.com/v1/notfications/webhooks-events/8PT597110X687430LKGECATA/resend",
      "rel": "resend",
      "method": "POST"
    }
  ]
}', true);

        $expectedParams = ['12334456'];

        $expectedMethod = 'resendEventNotification';

        $mockClient = $this->mock_client($expectedResponse, $expectedMethod, $this->getCredentials(), true);

        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}('8PT597110X687430LKGECATA', $expectedParams));
    }
}
