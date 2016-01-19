<?php namespace Srmklive\PayPal\Services;

use Srmklive\PayPal\Traits\PayPalRequest As PayPalAPIRequest;

class ExpressCheckout
{
    // Integrate PayPal Request trait
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
            'PAYMENTREQUEST_0_NOTIFYURL'        =>  config('paypal.notify_url')
        ];

        foreach ($tmp as $k=>$v) {
            $post[$k] = $v;
        }

        $response = self::doPayPalRequest('DoExpressCheckoutPayment', $post);

        return $response;
    }

}
