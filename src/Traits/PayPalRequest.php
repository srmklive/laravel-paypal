<?php namespace Srmklive\PayPal\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

trait PayPalRequest
{
    
    /**
     * @var Client
     */
    private static $client;

    /**
     * @var Array
     */
    private static $config;

    /**
     * Function To Set PayPal API Configuration
     */
    private static function setConfig()
    {
        
        // Setting Http Client
        self::$client = new Client();

        $paypal = config('paypal');

        // Setting Default PayPal Mode If not set
        if (empty($paypal['mode']) || !in_array($paypal['mode'], ['sandbox', 'live'])) {
            $paypal['mode'] = 'live';
        }

        $mode = $paypal['mode'];

        // Getting PayPal API Credentials
        foreach ($paypal[$mode] as $key=>$value) {
            self::$config[$key] = $value;
        }

        // Setting API Endpoints
        if ($paypal['mode'] == 'sandbox') {
            self::$config['api_url'] = !empty(self::$config['secret']) ?
                'https://api-3t.sandbox.paypal.com/nvp' : 'https://api.sandbox.paypal.com/nvp';

            self::$config['gateway_url'] = 'https://www.sandbox.paypal.com';
        } else {
            self::$config['api_url'] = !empty(self::$config['secret']) ?
                'https://api-3t.paypal.com/nvp' : 'https://api.paypal.com/nvp';

            self::$config['gateway_url'] = 'https://www.paypal.com';
        }

        // Adding params outside sandbox / live array
        self::$config['payment_action'] = $paypal['payment_action'];
        self::$config['currency'] = $paypal['currency'];
        self::$config['notify_url'] = $paypal['notify_url'];

        unset($paypal);
    }

    /**
     * Verify PayPal IPN Response
     *
     * @param $post
     * @return array
     */
    public static function verifyIPN($post)
    {
        $response = self::doPayPalRequest('verifyipn',$post);

        return $response;
    }

    /**
     * Refund PayPal Transaction
     *
     * @param $transaction
     * @return array
     */
    public static function refundTransaction($transaction)
    {
        $post = [
            'TRANSACTIONID' =>  $transaction
        ];

        $response = self::doPayPalRequest('RefundTransaction',$post);

        return $response;
    }

    /**
     * Search Transactions On PayPal
     *
     * @param array $post
     * @return array
     */
    public static function searchTransactions($post)
    {
        $response = self::doPayPalRequest('TransactionSearch', $post);

        return $response;
    }

    /**
     * Function To Perform PayPal API Request
     *
     * @param $method
     * @param $params
     * @return array
     */
    private static function doPayPalRequest($method, $params)
    {
        if (empty(self::$config))
            self::setConfig();

        // Setting API Credentials, Version & Method
        $post = [
            'USER'      => self::$config['username'],
            'PWD'       => self::$config['password'],
            'SIGNATURE' => self::$config['secret'],
            'VERSION'   => 123,
            'METHOD'    => $method,
        ];

        // Checking Whether The Request Is PayPal IPN Response
        if ($method == 'verifyipn') {
            unset($post['method']);

            $post_url = self::$config['gateway_url'].'/cgi-bin/webscr';
        } else {
            $post_url = self::$config['api_url'];
        }

        foreach ($params as $key=>$value) {
            $post[$key] = $value;
        }

        try {
            $request = self::$client->post($post_url, [
                'form_params' => $post
            ]);

            $response = $request->getBody(true);
            $response = self::retrieveData($response);

            if ($method == 'SetExpressCheckout') {
                if (!empty($response['TOKEN'])) {
                    $response['paypal_link'] = self::$config['gateway_url'] .
                        '/webscr?cmd=_express-checkout&token=' . $response['TOKEN'];
                } else {
                    return [
                        'type'      => 'error',
                        'message'   => trans('paypal::error.paypal_connection_error')
                    ];
                }
            }

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

    /**
     * Parse PayPal NVP Response
     *
     * @param $string
     * @return array
     */
    private static function retrieveData($string)
    {
        $response = array();
        parse_str($string, $response);
        return $response;
    }

}
