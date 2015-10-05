<?php namespace Srmklive\PayPal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Lang;

class ExpressCheckout
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
     * PayPal Processor Constructor
     */
    public function __construct()
    {
        // Setting Http Client
        self::$client = new Client();

        // Setting PayPal API Credentials
        $this->setConfig();
    }

    /**
     * Function To Set PayPal API Configuration
     */
    private static function setConfig()
    {
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

        unset($paypal);
    }

    /**
     * Function To SetExpressCheckout PayPal API Operation
     *
     * @param $data
     * @return array
     */
    public static function setExpressCheckout($data)
    {
        $num = 0;
        $post = [];

        foreach ($data['items'] as $item) {
            $tmp = [
                'L_PAYMENTREQUEST_0_NAME'.$num  =>  $item['name'],
                'L_PAYMENTREQUEST_0_AMT'.$num   =>  $item['price']
            ];

            foreach ($tmp as $k=>$v) {
                $post[$k] = $v;
            }

            $num++;
        }

        $tmp = [
            'PAYMENTREQUEST_0_ITEMAMT'          =>  $data['total'],
            'PAYMENTREQUEST_0_AMT'              =>  $data['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION'    =>  !empty(self::$config['payment_action']) ? self::$config['payment_action'] : 'Sale',
            'PAYMENTREQUEST_0_CURRENCYCODE'     =>  !empty(self::$config['currency']) ? self::$config['currency'] : 'USD',
            'PAYMENTREQUEST_0_DESC'             =>  $data['invoice_description'],
            'PAYMENTREQUEST_0_INVNUM'           =>  $data['invoice_id'],
            'NOSHIPPING'                        =>  1,
            'RETURNURL'                         =>  $data['return_url'],
            'CANCELURL'                         =>  $data['cancel_url'],
        ];

        foreach ($tmp as $k=>$v) {
            $post[$k] = $v;
        }

        $response = self::doPayPalRequest('SetExpressCheckout', $post);

        return $response;
    }

    /**
     * Function To Perform GetExpressCheckoutDetails PayPal API Operation
     *
     * @param $token
     * @return array
     */
    public static function getExpressCheckoutDetails($token)
    {
        $post = [
            'TOKEN' => $token
        ];

        $response = self::doPayPalRequest('GetExpressCheckoutDetails', $post);

        return $response;
    }

    /**
     * Function To Perform DoExpressCheckoutPayment PayPal API Operation
     *
     * @param $data
     * @param $token
     * @param $payerid
     * @return array
     */
    public static function doExpressCheckoutPayment($data, $token, $payerid)
    {
        $num = 0;
        $post = [];

        foreach ($data['items'] as $item) {
            $tmp = [
                'L_PAYMENTREQUEST_0_NAME'.$num  =>  $item['name'],
                'L_PAYMENTREQUEST_0_AMT'.$num   =>  $item['price']
            ];

            foreach ($tmp as $k=>$v) {
                $post[$k] = $v;
            }

            $num++;
        }

        $tmp = [
            'TOKEN'                             =>  $token,
            'PAYERID'                           =>  $payerid,
            'PAYMENTREQUEST_0_ITEMAMT'          =>  $data['total'],
            'PAYMENTREQUEST_0_AMT'              =>  $data['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION'    =>  !empty(self::$config['payment_action']) ? self::$config['payment_action'] : 'Sale',
            'PAYMENTREQUEST_0_CURRENCYCODE'     =>  !empty(self::$config['currency']) ? self::$config['currency'] : 'USD',
            'PAYMENTREQUEST_0_DESC'             =>  $data['invoice_description'],
            'PAYMENTREQUEST_0_INVNUM'           =>  $data['invoice_id'],
            'PAYMENTREQUEST_0_NOTIFYURL'        =>  $data['notify_url']
        ];

        foreach ($tmp as $k=>$v) {
            $post[$k] = $v;
        }

        $response = self::doPayPalRequest('DoExpressCheckoutPayment', $post);

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
                        'message'   => Lang::get('cart.error.paypal_connection_error')
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
     */
    private static function retrieveData($string)
    {
        $response = array();
        parse_str($string, $response);
        return $response;
    }
}
