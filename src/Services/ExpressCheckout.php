<?php

namespace Srmklive\PayPal\Services;

use Illuminate\Support\Collection;
use Srmklive\PayPal\Traits\PayPalRequest as PayPalAPIRequest;
use Srmklive\PayPal\Traits\RecurringProfiles;

class ExpressCheckout
{
    // Integrate PayPal Request trait
    use PayPalAPIRequest, RecurringProfiles;

    /**
     * PayPal Processor Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // Setting PayPal API Credentials
        $this->setConfig($config);

        $this->httpBodyParam = 'form_params';

        $this->options = [];
    }

    /**
     * Set Http Client request body param. Should only be called when Guzzle version 5 is used.
     */
    public function setPreviousHttpBodyParam()
    {
        $this->httpBodyParam = 'body';
    }

    /**
     * Set ExpressCheckout API endpoints & options.
     *
     * @param array $credentials
     *
     * @return void
     */
    public function setExpressCheckoutOptions($credentials)
    {
        // Setting API Endpoints
        if ($this->mode === 'sandbox') {
            $this->config['api_url'] = !empty($this->config['secret']) ?
                'https://api-3t.sandbox.paypal.com/nvp' : 'https://api.sandbox.paypal.com/nvp';

            $this->config['gateway_url'] = 'https://www.sandbox.paypal.com';
        } else {
            $this->config['api_url'] = !empty($this->config['secret']) ?
                'https://api-3t.paypal.com/nvp' : 'https://api.paypal.com/nvp';

            $this->config['gateway_url'] = 'https://www.paypal.com';
        }

        // Adding params outside sandbox / live array
        $this->config['payment_action'] = $credentials['payment_action'];
        $this->config['notify_url'] = $credentials['notify_url'];
        $this->config['locale'] = $credentials['locale'];
    }

    /**
     * Set cart item details for PayPal.
     *
     * @param array $items
     *
     * @return \Illuminate\Support\Collection
     */
    protected function setCartItems($items)
    {
        return (new Collection($items))->map(function ($item, $num) {
            return [
                'L_PAYMENTREQUEST_0_NAME'.$num => $item['name'],
                'L_PAYMENTREQUEST_0_AMT'.$num  => $item['price'],
                'L_PAYMENTREQUEST_0_QTY'.$num  => isset($item['qty']) ? $item['qty'] : 1,
            ];
        })->flatMap(function ($value) {
            return $value;
        });
    }

    /**
     * Set Recurring payments details for SetExpressCheckout API call.
     *
     * @param array $data
     * @param bool  $subscription
     */
    protected function setExpressCheckoutRecurringPaymentConfig($data, $subscription = false)
    {
        $this->post = $this->post->merge([
            'L_BILLINGTYPE0'                    => ($subscription) ? 'RecurringPayments' : 'MerchantInitiatedBilling',
            'L_BILLINGAGREEMENTDESCRIPTION0'    => !empty($data['subscription_desc']) ?
                $data['subscription_desc'] : $data['invoice_description'],
        ]);
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
        $this->post = $this->setCartItems($data['items'])->merge([
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

        $this->setExpressCheckoutRecurringPaymentConfig($data, $subscription);

        $response = $this->doPayPalRequest('SetExpressCheckout');

        return collect($response)->merge([
            'paypal_link' => !empty($response['TOKEN']) ? $this->config['gateway_url'].'/webscr?cmd=_express-checkout&token='.$response['TOKEN'] : null,
        ])->toArray();
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
        $this->setRequestData([
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
        $this->post = $this->setCartItems($data['items'])->merge([
            'TOKEN'                             => $token,
            'PAYERID'                           => $payerid,
            'PAYMENTREQUEST_0_ITEMAMT'          => $data['total'],
            'PAYMENTREQUEST_0_AMT'              => $data['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION'    => !empty($this->config['payment_action']) ? $this->config['payment_action'] : 'Sale',
            'PAYMENTREQUEST_0_CURRENCYCODE'     => $this->currency,
            'PAYMENTREQUEST_0_DESC'             => $data['invoice_description'],
            'PAYMENTREQUEST_0_INVNUM'           => $data['invoice_id'],
            'PAYMENTREQUEST_0_NOTIFYURL'        => $this->notifyUrl,
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
        $this->setRequestData(
            array_merge($data, [
                'AUTHORIZATIONID' => $authorization_id,
                'AMT'             => $amount,
                'COMPLETETYPE'    => $complete,
                'CURRENCYCODE'    => $this->currency,
            ])
        );

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
        $this->setRequestData(
            array_merge($data, [
                'AUTHORIZATIONID' => $authorization_id,
                'AMT'             => $amount,
            ])
        );

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
        $this->setRequestData(
            array_merge($data, [
                'AUTHORIZATIONID' => $authorization_id,
            ])
        );

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
        $this->setRequestData([
            'TOKEN' => $token,
        ]);

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
        $this->post = $this->setRequestData($data)->merge([
            'TOKEN' => $token,
        ]);

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
        $this->setRequestData([
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
        $this->post = $this->setRequestData($data)->merge([
            'PROFILEID' => $id,
        ]);

        return $this->doPayPalRequest('UpdateRecurringPaymentsProfile');
    }

    /**
     * Change Recurring payment profile status on PayPal.
     *
     * @param string $id
     * @param string $status
     *
     * @return array|\Psr\Http\Message\StreamInterface
     */
    protected function manageRecurringPaymentsProfileStatus($id, $status)
    {
        $this->setRequestData([
            'PROFILEID' => $id,
            'ACTION'    => $status,
        ]);

        return $this->doPayPalRequest('ManageRecurringPaymentsProfileStatus');
    }

    /**
     * Cancel RecurringPaymentsProfile on PayPal.
     *
     * @param string $id
     *
     * @return array
     */
    public function cancelRecurringPaymentsProfile($id)
    {
        return $this->manageRecurringPaymentsProfileStatus($id, 'Cancel');
    }

    /**
     * Suspend an active RecurringPaymentsProfile on PayPal.
     *
     * @param string $id
     *
     * @return array
     */
    public function suspendRecurringPaymentsProfile($id)
    {
        return $this->manageRecurringPaymentsProfileStatus($id, 'Suspend');
    }

    /**
     * Reactivate a suspended RecurringPaymentsProfile on PayPal.
     *
     * @param string $id
     *
     * @return array
     */
    public function reactivateRecurringPaymentsProfile($id)
    {
        return $this->manageRecurringPaymentsProfileStatus($id, 'Reactivate');
    }
}
