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

        $this->setRequestData([
            'actionType'        => 'PAY',
            'currencyCode'      => $this->currency,
            'receiverList'      => [
                'receiver'      => $data['receivers'],
            ],
            'returnUrl'         => $data['return_url'],
            'cancelUrl'         => $data['cancel_url'],
            'requestEnvelope'   => $this->setEnvelope(),
            'feesPayer'         => $data['payer'],
        ])->filter(function ($value, $key) use ($data) {
            return (($key === 'feesPayer') && empty($value)) ?: $value;
        });

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
        $post = [
            'requestEnvelope' => $this->setEnvelope(),
            'payKey'          => $payKey,
        ];

        $receiverOptions = [];
        foreach ($receivers as $receiver) {
            $tmp = [];

            $tmp['receiver'] = [
                'email' => $receiver['email'],
            ];

            $tmp['invoiceData'] = [];
            foreach ($receiver['invoice_data'] as $invoice) {
                $tmp['invoiceData']['item'][] = $invoice;
            }

            if (isset($receiver['description'])) {
                $tmp['description'] = $receiver['description'];
            }

            $receiverOptions[] = $tmp;
            unset($tmp);
        }

        $post['receiverOptions'] = $receiverOptions;

        $response = $this->doPayPalRequest('SetPaymentOptions', $post);

        return $response;
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

        return $this->doPayPalRequest($operation, [
            'requestEnvelope' => $this->setEnvelope(),
            'payKey'          => $payKey,
        ]);
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
        // Check configuration settings. Reset them if empty.
        if (empty($this->config)) {
            self::setConfig();
        }

        // Throw exception if configuration is still not set.
        if (empty($this->config)) {
            throw new \Exception('PayPal api settings not found.');
        }

        $post_url = $this->config['api_url'].'/'.$method;

        $post = [];
        foreach ($params as $key => $value) {
            $post[$key] = $value;
        }

        // Merge $options array if set.
        if (!empty($this->options)) {
            $post = array_merge($post, $this->options);
        }

        try {
            $request = $this->client->post($post_url, [
                'json'    => $post,
                'headers' => $this->setHeaders(),
            ]);

            $response = $request->getBody();

            return \GuzzleHttp\json_decode($response, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return [
            'type'      => 'error',
            'message'   => $message,
        ];
    }
}
