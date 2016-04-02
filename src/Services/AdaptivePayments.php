<?php namespace Srmklive\PayPal\Services;

use Srmklive\PayPal\Traits\PayPalRequest As PayPalAPIRequest;

class AdaptivePayments
{
    use PayPalAPIRequest;

    /**
     * PayPal Processor Constructor
     */
    public function __construct()
    {
        // Setting PayPal API Credentials
        $this->setConfig();
    }

    /**
     * Function To Set PayPal API Configuration
     */
    private function setConfig()
    {
        // Setting Http Client
        $this->client = new Client();

        $paypal = config('paypal');

        // Setting Default PayPal Mode If not set
        if (empty($paypal['mode']) || !in_array($paypal['mode'], ['sandbox', 'live'])) {
            $paypal['mode'] = 'live';
        }

        $mode = $paypal['mode'];

        // Getting PayPal API Credentials
        foreach ($paypal[$mode] as $key=>$value) {
            $this->config[$key] = $value;
        }

        // Setting API Endpoints
        if ($paypal['mode'] == 'sandbox') {
            $this->config['api_url'] = 'https://svcs.sandbox.paypal.com/AdaptivePayments';
            $this->config['gateway_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $this->config['api_url'] = 'https://svcs.paypal.com/AdaptivePayments';
            $this->config['gateway_url'] = 'https://www.paypal.com/cgi-bin/webscr';
        }

        // Adding params outside sandbox / live array
        $this->config['currency'] = $paypal['currency'];

        unset($paypal);
    }


    /**
     * Set Adaptive Payments API request headers
     *
     * @return array
     */
    private function setHeaders()
    {
        $headers = [
            'X-PAYPAL-SECURITY-USERID: ' . $this->config['username'],
            'X-PAYPAL-SECURITY-PASSWORD: ' . $this->config['password'],
            'X-PAYPAL-SECURITY-SIGNATURE: ' . $this->config['secret'],
            'X-PAYPAL-REQUEST-DATA-FORMAT: NV',
            'X-PAYPAL-RESPONSE-DATA-FORMAT: NV',
            'X-PAYPAL-APPLICATION-ID: ' . $this->config['app_id'],
        ];

        return $headers;
    }

    /**
     * Set Adaptive Payments API request envelope
     *
     * @return array
     */
    private function setEnvelope()
    {
        $envelope = [
            'errorLanguage' => 'en_US',
            'detailLevel' => 'ReturnAll',
        ];

        return $envelope;
    }

    /**
     * Function to perform Adaptive Payments API's PAY operation
     *
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function createPayRequest($data)
    {
        $post = [
            'actionType' => 'PAY',
            'currencyCode' => $this->config['currency'],
            'receiverList' => [
                'receiver' => $data['receivers']
            ]
        ];

        if (!empty($data['return_url']) && !empty($data['cancel_url'])) {
            $post['returnUrl'] = $data['return_url'];
            $post['cancelUrl'] = $data['cancel_url'];
        } else {
            throw new \Exception('Return & Cancel URL should be specified');
        }

        $post['requestEnvelope'] = $this->setEnvelope();

        $response = $this->doPayPalRequest('PAY', $post);

        return $response;
    }

    /**
     * Function to perform Adaptive Payments API's SetPaymentOptions operation
     *
     * @param $payKey
     * @param $receivers
     * @return array
     */
    public function setPaymentOptions($payKey, $receivers)
    {
        $post = [
            'requestEnvelope' => $this->setEnvelope(),
            'payKey' => $payKey,
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

            $receiverOptions[] = $tmp;
            unset($tmp);
        }

        $post['receiverOptions'] = $receiverOptions;

        $response = $this->doPayPalRequest('SetPaymentOptions', $post);

        return $response;
    }

    /**
     * Function to perform Adaptive Payments API's GetPaymentOptions operation
     *
     * @param $payKey
     * @return array
     */
    public function getPaymentOptions($payKey)
    {
        $post = [
            'requestEnvelope' => $this->setEnvelope(),
            'payKey' => $payKey,
        ];

        $response = $this->doPayPalRequest('GetPaymentOptions', $post);

        return $response;
    }

    /**
     * Get PayPal redirect url for processing payment
     *
     * @param $option
     * @param $payKey
     * @return string
     */
    public function getRedirectUrl($option, $payKey)
    {
        $url = $this->config['gateway_url'] . '?cmd=';

        if ($option == 'approved')
            $url .= '_ap-payment&paykey=' . $payKey;
        elseif ($option == 'pre-approved')
            $url .= '_ap-preapproval&preapprovalkey=' . $payKey;

        return $url;
    }

    /**
     * Function To Perform PayPal API Request
     *
     * @param $method
     * @param $params
     * @return array
     */
    private function doPayPalRequest($method, $params)
    {
        if (empty($this->config))
            self::setConfig();



        $post_url = $this->config['api_url'] . '/' . $method;

        foreach ($params as $key=>$value) {
            $post[$key] = $value;
        }

        try {
            $request = $this->client->post($post_url, [
                'form_params' => $post,
                'headers' => $this->setHeaders(),
            ]);

            $response = $request->getBody(true);
            $response = $this->retrieveData($response);

            return $response;
        } catch (ClientException $e) {
            $message = $e->getRequest() . " " . $e->getResponse();
        } catch (ServerException $e) {
            $message = $e->getRequest(). " " . $e->getResponse();
        } catch (BadResponseException $e) {
            $message = $e->getRequest(). " " . $e->getResponse();
        }

        return [
            'type'      => 'error',
            'message'   => $message
        ];
    }
}
