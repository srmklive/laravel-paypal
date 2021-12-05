<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait WebHooksEvents
{
    /**
     * List all events types for web hooks.
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/webhooks/v1/#webhooks-event-types_list
     */
    public function listEventTypes()
    {
        $this->apiEndPoint = 'v1/notifications/webhooks-event-types';

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * List all events notifications for web hooks.
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/webhooks/v1/#webhooks-events_list
     */
    public function listEvents()
    {
        $this->apiEndPoint = 'v1/notifications/webhooks-events';

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * List all events notifications for web hooks.
     *
     * @param string $event_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/webhooks/v1/#webhooks-events_get
     */
    public function showEventDetails(string $event_id)
    {
        $this->apiEndPoint = "v1/notifications/webhooks-events/{$event_id}";

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Resend notification for the event.
     *
     * @param string $event_id
     * @param array  $items
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/webhooks/v1/#webhooks-events_resend
     */
    public function resendEventNotification(string $event_id, array $items)
    {
        $this->apiEndPoint = "v1/notifications/webhooks-events/{$event_id}/resend";

        $this->options['json'] = [
            'webhook_ids' => $items,
        ];
        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
