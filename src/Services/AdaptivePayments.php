<?php namespace Srmklive\PayPal\Services;

use PayPal\Service\AdaptivePaymentsService;
use PayPal\Types\AP\PayRequest;
use PayPal\Types\AP\Receiver;
use PayPal\Types\AP\ReceiverList;
use PayPal\Types\Common\RequestEnvelope;
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

        $paypal = config('paypal');

        // Setting Default PayPal Mode If not set
        if (empty($paypal['mode']) || !in_array($paypal['mode'], ['sandbox', 'live'])) {
            $paypal['mode'] = 'live';
        }

        $mode = $paypal['mode'];

        // Getting PayPal API Credentials
        foreach ($paypal[$mode] as $key => $value) {
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

        // Set mode
        $this->config['mode'] = $mode;

        // Setting Http Client
        $this->client = $this->setClient();

        unset($paypal);
    }

    /**
     * Function to Guzzle Client class object
     *
     * @return Client
     */
    protected function setClient()
    {
        $config = [
            "acct1.UserName" => $this->config['username'],
            "acct1.Password" => $this->config['password'],
            "acct1.Signature" => $this->config['secret'],
            "acct1.AppId" => $this->config['app_id'],
            "mode" => $this->config['mode']
        ];

        return new AdaptivePaymentsService($config);
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
        $tmp = [];
        foreach ($data['receivers'] as $i => $receiver) {
            $tmp[$i] = new Receiver;
            $tmp[$i]->email = $receiver['email'];
            $tmp[$i]->amount = $receiver['amount'];
        }

        $receivers = $tmp;
        unset($tmp);

        $receiverList = new ReceiverList($receivers);

        $payRequest = new PayRequest(
            new RequestEnvelope("en_US"),
            'PAY',
            $data['cancel_url'],
            $this->config['currency'],
            $receiverList,
            $data['return_url']
        );

        $response = $this->doPayPalRequest('Pay', $payRequest);

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
     * @return array|\Psr\Http\Message\StreamInterface
     * @throws \Exception
     */
    private function doPayPalRequest($method, $params)
    {
        if (empty($this->config))
            self::setConfig();

        try {
            /* wrap API method calls on the service object with a try catch */
            $response = $this->client->$method($params);
            $response = $this->retrieveData($response);

            return $response;
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return [
            'type'      => 'error',
            'message'   => $message
        ];

    }

    /**
     * Parse response from PayPal
     *
     * @param $data
     * @return array
     */
    private function retrieveData($data)
    {
        if (is_array($data) || is_object($data))
        {
            $result = array();
            foreach ($data as $key => $value)
            {
                $result[$key] = $this->retrieveData($value);
            }
            return $result;
        }
        return $data;
    }
}
