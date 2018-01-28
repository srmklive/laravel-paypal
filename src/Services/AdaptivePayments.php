<?php

namespace Srmklive\PayPal\Services;

use Srmklive\PayPal\Traits\PayPalRequest as PayPalAPIRequest;

class AdaptivePayments
{
    use PayPalAPIRequest;

    /**
     * PayPal Processor Constructor.
     */
    public function __construct()
    {
        // Setting PayPal API Credentials
        $this->setConfig();
    }

    /**
     * Set AdaptivePayments API endpoints & options.
     *
     * @return void
     */
    public function setAdaptivePaymentsOptions()
    {
        if ($this->mode == 'sandbox') {
            $this->config['api_url'] = 'https://svcs.sandbox.paypal.com/AdaptivePayments';
            $this->config['gateway_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $this->config['api_url'] = 'https://svcs.paypal.com/AdaptivePayments';
            $this->config['gateway_url'] = 'https://www.paypal.com/cgi-bin/webscr';
        }
        $this->config['notify_url'] = config('paypal.notify_url');
    }

    /**
     * Set Adaptive Payments API request headers.
     *
     * @return array
     */
    private function setHeaders()
    {
        $headers = [
            'X-PAYPAL-SECURITY-USERID'      => $this->config['username'],
            'X-PAYPAL-SECURITY-PASSWORD'    => $this->config['password'],
            'X-PAYPAL-SECURITY-SIGNATURE'   => $this->config['signature'],
            'X-PAYPAL-REQUEST-DATA-FORMAT'  => 'JSON',
            'X-PAYPAL-RESPONSE-DATA-FORMAT' => 'JSON',
            'X-PAYPAL-APPLICATION-ID'       => $this->config['app_id'],
        ];

        return $headers;
    }

    /**
     * Set Adaptive Payments API request envelope.
     *
     * @return array
     */
    private function setEnvelope()
    {
        $envelope = [
            'errorLanguage' => 'en_US',
            'detailLevel'   => 'ReturnAll',
        ];

        return $envelope;
    }

    /**
     * Set request details for Pay API operation.
     *
     * @param array $data
     *
     * @return void
     */
    private function setPayRequestDetails($data)
    {
        $this->post = $this->setRequestData([
            'actionType'   => 'PAY',
            'currencyCode' => $this->currency,
            'receiverList' => [
                'receiver' => $data['receivers'],
            ],
            'returnUrl'       => $data['return_url'],
            'cancelUrl'       => $data['cancel_url'],
            'requestEnvelope' => $this->setEnvelope(),
            'feesPayer'       => $data['payer'],
        ])->filter(function ($value, $key) {
            return (($key === 'feesPayer') && empty($value)) ? null : $value;
        });
    }

    /**
     * Function to perform Adaptive Payments API's PAY operation.
     *
     * @param array $data
     *
     * @throws \Exception
     *
     * @return array
     */
    public function createPayRequest($data)
    {
        if (empty($data['return_url']) && empty($data['cancel_url'])) {
            throw new \Exception('Return & Cancel URL should be specified');
        }

        $this->setPayRequestDetails($data);

        return $this->doPayPalRequest('Pay');
    }

    /**
     * Function to perform Adaptive Payments API's SetPaymentOptions operation.
     *
     * @param string $payKey
     * @param array  $receivers
     *
     * @return array
     */
    public function setPaymentOptions($payKey, $receivers)
    {
        $this->post = $this->setRequestData([
            'requestEnvelope' => $this->setEnvelope(),
            'payKey'          => $payKey,
        ])->merge([
            'receiverOptions' => $this->setPaymentOptionsReceiverDetails($receivers),
        ]);

        return $this->doPayPalRequest('SetPaymentOptions');
    }

    /**
     * Function to perform Adaptive Payments API's GetPaymentOptions operation.
     *
     * @param string $payKey
     * @param bool   $details
     *
     * @return array
     */
    public function getPaymentOptions($payKey, $details = false)
    {
        $operation = ($details) ? 'PaymentDetails' : 'GetPaymentOptions';

        $this->setRequestData([
            'requestEnvelope' => $this->setEnvelope(),
            'payKey'          => $payKey,
        ]);

        return $this->doPayPalRequest($operation);
    }

    /**
     * Function to perform Adaptive Payments API's PaymentDetails operation.
     *
     * @param string $payKey
     *
     * @return array
     */
    public function getPaymentDetails($payKey)
    {
        return $this->getPaymentOptions($payKey, true);
    }

    /**
     * Get PayPal redirect url for processing payment.
     *
     * @param string $option
     * @param string $payKey
     *
     * @return string
     */
    public function getRedirectUrl($option, $payKey)
    {
        $url = $this->config['gateway_url'].'?cmd=';

        if ($option == 'approved') {
            $url .= '_ap-payment&paykey='.$payKey;
        } elseif ($option == 'pre-approved') {
            $url .= '_ap-preapproval&preapprovalkey='.$payKey;
        }

        return $url;
    }

    /**
     * Set receiver details for SetPaymentOptions.
     *
     * @param array $receivers
     *
     * @return array
     */
    private function setPaymentOptionsReceiverDetails($receivers)
    {
        return collect($receivers)->map(function ($receiver) {
            $item = [];

            $item['receiver'] = [
                'email' => $receiver['email'],
            ];

            $item['invoiceData']['item'] = collect($receiver['invoice_data'])->map(function ($invoice) {
                return $invoice;
            })->toArray();

            $item['description'] = $receiver['description'];

            return $item;
        })->toArray();
    }

    /**
     * Create request payload to be sent to PayPal.
     *
     * @param string $method
     */
    private function createRequestPayload($method)
    {
        $this->apiUrl = $this->config['api_url'].'/'.$method;

        $this->post = $this->post->merge($this->options);
    }

    /**
     * Perform PayPal API request & return response.
     *
     * @throws \Exception
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    private function makeHttpRequest()
    {
        try {
            return $this->client->post($this->apiUrl, [
                'json'    => $this->post->toArray(),
                'headers' => $this->setHeaders(),
            ])->getBody();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \Exception(collect($e->getTrace())->implode('\n'));
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            throw new \Exception(collect($e->getTrace())->implode('\n'));
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            throw new \Exception(collect($e->getTrace())->implode('\n'));
        }
    }

    /**
     * Function To Perform PayPal API Request.
     *
     * @param string $method
     *
     * @throws \Exception
     *
     * @return array|mixed|\Psr\Http\Message\StreamInterface
     */
    private function doPayPalRequest($method)
    {
        // Setup PayPal API Request Payload
        $this->createRequestPayload($method);

        try {
            $response = $this->makeHttpRequest();

            return \GuzzleHttp\json_decode($response, true);
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return [
            'type'    => 'error',
            'message' => $message,
        ];
    }
}
