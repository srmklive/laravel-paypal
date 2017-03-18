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
        $this->post = collect($data['items'])->map(function ($item, $num) {
            return [
                'L_PAYMENTREQUEST_0_NAME'.$num  => $item['name'],
                'L_PAYMENTREQUEST_0_AMT'.$num   => $item['price'],
                'L_PAYMENTREQUEST_0_QTY'.$num   => $item['qty'],
            ];
        })->flatMap(function ($value) {
            return $value;
        })->merge([
            'PAYMENTREQUEST_0_ITEMAMT'          => $data['total'],
            'PAYMENTREQUEST_0_AMT'              => $data['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION'    => $this->paymentAction,
            'PAYMENTREQUEST_0_CURRENCYCODE'     => $this->currency,
            'PAYMENTREQUEST_0_DESC'             => $data['invoice_description'],
            'PAYMENTREQUEST_0_INVNUM'           => $data['invoice_id'],
            'NOSHIPPING'                        => 1,
            'RETURNURL'                         => $data['return_url'],
            'CANCELURL'                         => $data['cancel_url'],
            'LOCALE'                            => $this->locale,
        ]);

        if ($subscription) {
            $this->post->merge([
                'L_BILLINGTYPE0'                    => 'RecurringPayment',
                'L_BILLINGAGREEMENTDESCRIPTION0'    => !empty($data['subscription_desc']) ?
                    $data['subscription_desc'] : $data['invoice_description'],
            ]);
        }

        $response = $this->doPayPalRequest('SetExpressCheckout');

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
        $this->post = collect([
            'TOKEN' => $token,
        ]);

        return $this->doPayPalRequest('GetExpressCheckoutDetails');
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
        $this->post = collect($data['items'])->map(function ($item, $num) {
            return [
                'L_PAYMENTREQUEST_0_NAME'.$num  => $item['name'],
                'L_PAYMENTREQUEST_0_AMT'.$num   => $item['price'],
                'L_PAYMENTREQUEST_0_QTY'.$num   => $item['qty'],
            ];
        })->flatMap(function ($value) {
            return $value;
        })->merge([
            'TOKEN'                             => $token,
            'PAYERID'                           => $payerid,
            'PAYMENTREQUEST_0_ITEMAMT'          => $data['total'],
            'PAYMENTREQUEST_0_AMT'              => $data['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION'    => !empty($this->config['payment_action']) ? $this->config['payment_action'] : 'Sale',
            'PAYMENTREQUEST_0_CURRENCYCODE'     => $this->currency,
            'PAYMENTREQUEST_0_DESC'             => $data['invoice_description'],
            'PAYMENTREQUEST_0_INVNUM'           => $data['invoice_id'],
            'PAYMENTREQUEST_0_NOTIFYURL'        => config('paypal.notify_url'),
        ]);

        return $this->doPayPalRequest('DoExpressCheckoutPayment');
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
        $this->post = collect($data)->merge([
            'AUTHORIZATIONID' => $authorization_id,
            'AMT'             => $amount,
            'COMPLETETYPE'    => $complete,
            'CURRENCYCODE'    => $this->currency,
        ]);

        return $this->doPayPalRequest('DoCapture');
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
        $this->post = collect($data)->merge([
            'AUTHORIZATIONID' => $authorization_id,
            'AMT'             => $amount,
        ]);

        return $this->doPayPalRequest('DoAuthorization');
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
        $this->post = collect($data)->merge([
            'AUTHORIZATIONID' => $authorization_id,
        ]);

        return $this->doPayPalRequest('DoVoid');
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
        $this->post = [
            'TOKEN' => $token,
        ];

        return $this->doPayPalRequest('CreateBillingAgreement');
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
        $this->post = collect([
            'token' => $token,
        ])->merge($data);

        return $this->doPayPalRequest('CreateRecurringPaymentsProfile');
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
        $this->post = collect([
            'PROFILEID' => $id,
        ]);

        return $this->doPayPalRequest('GetRecurringPaymentsProfileDetails');
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
        $this->post = collect([
            'PROFILEID' => $id,
        ])->merge($data);

        return $this->doPayPalRequest('UpdateRecurringPaymentsProfile');
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
        $this->post = collect([
            'PROFILEID' => $id,
            'ACTION'    => 'Cancel',
        ]);

        return $this->doPayPalRequest('ManageRecurringPaymentsProfileStatus');
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
        $this->post = collect([
            'PROFILEID' => $id,
            'ACTION'    => 'Suspend',
        ]);

        return $this->doPayPalRequest('ManageRecurringPaymentsProfileStatus');
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
        $this->post = collect([
            'PROFILEID' => $id,
            'ACTION'    => 'Reactivate',
        ]);

        return $this->doPayPalRequest('ManageRecurringPaymentsProfileStatus');
    }
}
