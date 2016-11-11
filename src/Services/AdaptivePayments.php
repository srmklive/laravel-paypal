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
     * Set Adaptive Payments API request headers.
     *
     * @return array
     */
    private function setHeaders()
    {
        $headers = [
            'X-PAYPAL-SECURITY-USERID'      => $this->config['username'],
            'X-PAYPAL-SECURITY-PASSWORD'    => $this->config['password'],
            'X-PAYPAL-SECURITY-SIGNATURE'   => $this->config['secret'],
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
        $post = [
            'actionType'   => 'PAY',
            'currencyCode' => $this->currency,
            'receiverList' => [
                'receiver' => $data['receivers'],
            ],
        ];

        if (!empty($data['feesPayer'])) {
            $post['feesPayer'] = $data['payer'];
        }

        if (!empty($data['return_url']) && !empty($data['cancel_url'])) {
            $post['returnUrl'] = $data['return_url'];
            $post['cancelUrl'] = $data['cancel_url'];
        } else {
            throw new \Exception('Return & Cancel URL should be specified');
        }

        $post['requestEnvelope'] = $this->setEnvelope();

        $response = $this->doPayPalRequest('Pay', $post);

        return $response;
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
            
            if(isset($receiver['description'])){
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
     *
     * @return array
     */
    public function getPaymentOptions($payKey)
    {
        $post = [
            'requestEnvelope' => $this->setEnvelope(),
            'payKey'          => $payKey,
        ];

        $response = $this->doPayPalRequest('GetPaymentOptions', $post);

        return $response;
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
     * @param array  $params
     *
     * @throws \Exception
     *
     * @return array|mixed|\Psr\Http\Message\StreamInterface
     */
    private function doPayPalRequest($method, $params)
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

            $response = $request->getBody(true);
            $response = \GuzzleHttp\json_decode($response, true);

            return $response;
        } catch (ClientException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (ServerException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (BadResponseException $e) {
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
