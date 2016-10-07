<?php

namespace Srmklive\PayPal\Services;

use Srmklive\PayPal\Traits\PayPalRequest as PayPalAPIRequest;

class ExpressCheckout
{
    // Integrate PayPal Request trait
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
     * Function to perform SetExpressCheckout PayPal API operation.
     *
     * @param array $data
     * @param bool  $subscription
     *
     * @return array
     */
    public function setExpressCheckout($data, $subscription = false)
    {
        $num = 0;
        $post = [];

        foreach ($data['items'] as $item) {
            $tmp = [
                'L_PAYMENTREQUEST_0_NAME'.$num  => $item['name'],
                'L_PAYMENTREQUEST_0_AMT'.$num   => $item['price'],
                'L_PAYMENTREQUEST_0_QTY'.$num   => $item['qty'],
            ];

            foreach ($tmp as $k => $v) {
                $post[$k] = $v;
            }

            $num++;
        }

        $tmp = [
            'PAYMENTREQUEST_0_ITEMAMT'          => $data['total'],
            'PAYMENTREQUEST_0_AMT'              => $data['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION'    => !empty($this->config['payment_action']) ? $this->config['payment_action'] : 'Sale',
            'PAYMENTREQUEST_0_CURRENCYCODE'     => $this->currency,
            'PAYMENTREQUEST_0_DESC'             => $data['invoice_description'],
            'PAYMENTREQUEST_0_INVNUM'           => $data['invoice_id'],
            'NOSHIPPING'                        => 1,
            'RETURNURL'                         => $data['return_url'],
            'CANCELURL'                         => $data['cancel_url'],
        ];

        if ($subscription) {
            $post['L_BILLINGTYPE0'] = 'RecurringPayments';
            $post['L_BILLINGAGREEMENTDESCRIPTION0'] = !empty($data['subscription_desc']) ?
                $data['subscription_desc'] : $data['invoice_description'];
        }

        if (!empty($this->config['locale'])) {
            $post['LOCALECODE'] = $this->config['locale'];
        }
        
        foreach ($tmp as $k => $v) {
            $post[$k] = $v;
        }

        $response = $this->doPayPalRequest('SetExpressCheckout', $post);

        if (!empty($response['TOKEN'])) {
            $response['paypal_link'] = $this->config['gateway_url'].
                '/webscr?cmd=_express-checkout&token='.$response['TOKEN'];
        }

        return $response;
    }

    /**
     * Function to perform GetExpressCheckoutDetails PayPal API operation.
     *
     * @param string $token
     *
     * @return array
     */
    public function getExpressCheckoutDetails($token)
    {
        $post = [
            'TOKEN' => $token,
        ];

        $response = $this->doPayPalRequest('GetExpressCheckoutDetails', $post);

        return $response;
    }

    /**
     * Function to perform DoExpressCheckoutPayment PayPal API operation.
     *
     * @param array  $data
     * @param string $token
     * @param string $payerid
     *
     * @return array
     */
    public function doExpressCheckoutPayment($data, $token, $payerid)
    {
        $num = 0;
        $post = [];

        foreach ($data['items'] as $item) {
            $tmp = [
                'L_PAYMENTREQUEST_0_NAME'.$num  => $item['name'],
                'L_PAYMENTREQUEST_0_AMT'.$num   => $item['price'],
                'L_PAYMENTREQUEST_0_QTY'.$num   => $item['qty'],
            ];

            foreach ($tmp as $k => $v) {
                $post[$k] = $v;
            }

            $num++;
        }

        $tmp = [
            'TOKEN'                             => $token,
            'PAYERID'                           => $payerid,
            'PAYMENTREQUEST_0_ITEMAMT'          => $data['total'],
            'PAYMENTREQUEST_0_AMT'              => $data['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION'    => !empty($this->config['payment_action']) ? $this->config['payment_action'] : 'Sale',
            'PAYMENTREQUEST_0_CURRENCYCODE'     => $this->currency,
            'PAYMENTREQUEST_0_DESC'             => $data['invoice_description'],
            'PAYMENTREQUEST_0_INVNUM'           => $data['invoice_id'],
            'PAYMENTREQUEST_0_NOTIFYURL'        => config('paypal.notify_url'),
        ];

        foreach ($tmp as $k => $v) {
            $post[$k] = $v;
        }

        $response = $this->doPayPalRequest('DoExpressCheckoutPayment', $post);

        return $response;
    }

    /**
     * Function to perform DoCapture PayPal API operation.
     *
     * @param string $authorization_id Transaction ID
     * @param float  $amount           Amount to capture
     * @param string $complete         Indicates whether or not this is the last capture.
     * @param array  $data             Optional request fields
     *
     * @return array
     */
    public function doCapture($authorization_id, $amount, $complete = 'Complete', $data = [])
    {
        $response = $this->doPayPalRequest('DoCapture', array_merge($data, [
            'AUTHORIZATIONID' => $authorization_id,
            'AMT'             => $amount,
            'COMPLETETYPE'    => $complete,
            'CURRENCYCODE'    => $this->currency,
        ]));

        return $response;
    }

    /**
     * Function to perform DoAuthorization PayPal API operation.
     *
     * @param string $authorization_id Transaction ID
     * @param float  $amount           Amount to capture
     * @param array  $data             Optional request fields
     *
     * @return array
     */
    public function doAuthorization($authorization_id, $amount, $data = [])
    {
        $response = $this->doPayPalRequest('DoAuthorization', array_merge($data, [
            'AUTHORIZATIONID' => $authorization_id,
            'AMT'             => $amount,
        ]));

        return $response;
    }

    /**
     * Function to perform DoVoid PayPal API operation.
     *
     * @param string $authorization_id Transaction ID
     * @param array  $data             Optional request fields
     *
     * @return array
     */
    public function doVoid($authorization_id, $data = [])
    {
        $response = $this->doPayPalRequest('DoVoid', array_merge($data, [
            'AUTHORIZATIONID' => $authorization_id,
        ]));

        return $response;
    }

    /**
     * Function to perform CreateBillingAgreement PayPal API operation.
     *
     * @param string $token
     *
     * @return array
     */
    public function createBillingAgreement($token)
    {
        $post = [
            'TOKEN' => $token,
        ];

        $response = $this->doPayPalRequest('CreateBillingAgreement', $post);

        return $response;
    }

    /**
     * Function to perform CreateRecurringPaymentsProfile PayPal API operation.
     *
     * @param array  $data
     * @param string $token
     *
     * @return array
     */
    public function createRecurringPaymentsProfile($data, $token)
    {
        $post = [
            'token' => $token,
        ];

        foreach ($data as $key => $value) {
            $post[$key] = $value;
        }

        $response = $this->doPayPalRequest('CreateRecurringPaymentsProfile', $post);

        return $response;
    }

    /**
     * Function to perform GetRecurringPaymentsProfileDetails PayPal API operation.
     *
     * @param string $id
     *
     * @return array
     */
    public function getRecurringPaymentsProfileDetails($id)
    {
        $post = [
            'PROFILEID' => $id,
        ];

        $response = $this->doPayPalRequest('GetRecurringPaymentsProfileDetails', $post);

        return $response;
    }

    /**
     * Function to perform UpdateRecurringPaymentsProfile PayPal API operation.
     *
     * @param array  $data
     * @param string $id
     *
     * @return array
     */
    public function updateRecurringPaymentsProfile($data, $id)
    {
        $post = [
            'PROFILEID' => $id,
        ];

        foreach ($data as $key => $value) {
            $post[$key] = $value;
        }

        $response = $this->doPayPalRequest('UpdateRecurringPaymentsProfile', $post);

        return $response;
    }

    /**
     * Function to cancel RecurringPaymentsProfile on PayPal.
     *
     * @param string $id
     *
     * @return array
     */
    public function cancelRecurringPaymentsProfile($id)
    {
        $post = [
            'PROFILEID' => $id,
            'ACTION'    => 'Cancel',
        ];

        $response = $this->doPayPalRequest('ManageRecurringPaymentsProfileStatus', $post);

        return $response;
    }

    /**
     * Function to suspend an active RecurringPaymentsProfile on PayPal.
     *
     * @param string $id
     *
     * @return array
     */
    public function suspendRecurringPaymentsProfile($id)
    {
        $post = [
            'PROFILEID' => $id,
            'ACTION'    => 'Suspend',
        ];

        $response = $this->doPayPalRequest('ManageRecurringPaymentsProfileStatus', $post);

        return $response;
    }

    /**
     * Function to reactivate a suspended RecurringPaymentsProfile on PayPal.
     *
     * @param string $id
     *
     * @return array
     */
    public function reactivateRecurringPaymentsProfile($id)
    {
        $post = [
            'PROFILEID' => $id,
            'ACTION'    => 'Reactivate',
        ];

        $response = $this->doPayPalRequest('ManageRecurringPaymentsProfileStatus', $post);

        return $response;
    }
}
