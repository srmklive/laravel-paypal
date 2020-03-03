<?php

namespace Srmklive\PayPal\Services;

use Exception;
use Illuminate\Support\Collection;
use Psr\Http\Message\StreamInterface;
use Srmklive\PayPal\Traits\PayPalRequest as PayPalAPIRequest;
use Srmklive\PayPal\Traits\PayPalTransactions;
use Srmklive\PayPal\Traits\RecurringProfiles;

class ExpressCheckout
{
    // Integrate PayPal Request trait
    use PayPalAPIRequest;
    use PayPalTransactions;
    use RecurringProfiles;

    /**
     * ExpressCheckout constructor.
     *
     * @param array $config
     *
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        // Setting PayPal API Credentials
        $this->setConfig($config);

        $this->httpBodyParam = 'form_params';

        $this->options = [];
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
            $this->config['ipn_url'] = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $this->config['api_url'] = !empty($this->config['secret']) ?
                'https://api-3t.paypal.com/nvp' : 'https://api.paypal.com/nvp';

            $this->config['gateway_url'] = 'https://www.paypal.com';
            $this->config['ipn_url'] = 'https://ipnpb.paypal.com/cgi-bin/webscr';
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
     * @return Collection
     */
    protected function setCartItems($items)
    {
        return (new Collection($items))->map(static function ($item, $num) {
            return [
                'L_PAYMENTREQUEST_0_NAME'.$num  => $item['name'],
                'L_PAYMENTREQUEST_0_AMT'.$num   => $item['price'],
                'L_PAYMENTREQUEST_0_DESC'.$num  => isset($item['desc']) ? $item['desc'] : null,
                'L_PAYMENTREQUEST_0_QTY'.$num   => isset($item['qty']) ? $item['qty'] : 1,
            ];
        })->flatMap(static function ($value) {
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
        $billingType = $this->billingType;

        // Overwrite billing type if $subscription flag is enabled
        if ($subscription) {
            $billingType = 'RecurringPayments';
        }

        // Send L_BILLINGTYPE0 and L_BILLINGAGREEMENTDESCRIPTION0 only if there is billing type
        if (isset($billingType)) {
            $this->post = $this->post->merge([
                'L_BILLINGTYPE0'                 => $billingType,
                'L_BILLINGAGREEMENTDESCRIPTION0' => !empty($data['subscription_desc']) ?
                    $data['subscription_desc'] : $data['invoice_description'],
            ]);
        }
    }

    /**
     * Set item subtotal if available.
     *
     * @param array $data
     */
    protected function setItemSubTotal($data)
    {
        $this->subtotal = isset($data['subtotal']) ? $data['subtotal'] : $data['total'];
    }

    /**
     * Set shipping amount if available.
     *
     * @param array $data
     */
    protected function setShippingAmount($data)
    {
        if (isset($data['shipping'])) {
            $this->post = $this->post->merge([
                'PAYMENTREQUEST_0_SHIPPINGAMT' => $data['shipping'],
            ]);
        }
    }

    /**
     * Set tax amount if available.
     *
     * @param array $data
     */
    protected function setTaxAmount($data)
    {
        if (isset($data['tax'])) {
            $this->post = $this->post->merge([
                'PAYMENTREQUEST_0_TAXAMT' => $data['tax'],
            ]);
        }
    }

    /**
     * Set shipping discount if available.
     *
     * @param array $data
     */
    protected function setShippingDiscount($data)
    {
        if (isset($data['shipping_discount'])) {
            if ($data['shipping_discount'] > 0) {
                $data['shipping_discount'] *= -1;
            }

            $this->post = $this->post->merge([
                'PAYMENTREQUEST_0_SHIPDISCAMT' => $data['shipping_discount'],
                'PAYMENTREQUEST_0_AMT'         => round($data['total'] + $data['shipping_discount'], 2),
            ]);
        }
    }

    /**
     * Perform a SetExpressCheckout API call on PayPal.
     *
     * @param array $data
     * @param bool  $subscription
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function setExpressCheckout($data, $subscription = false)
    {
        $this->setItemSubTotal($data);

        $this->post = $this->setCartItems($data['items'])->merge([
            'PAYMENTREQUEST_0_ITEMAMT'       => $this->subtotal,
            'PAYMENTREQUEST_0_AMT'           => $data['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION' => $this->paymentAction,
            'PAYMENTREQUEST_0_CURRENCYCODE'  => $this->currency,
            'PAYMENTREQUEST_0_DESC'          => $data['invoice_description'],
            'PAYMENTREQUEST_0_INVNUM'        => $data['invoice_id'],
            'NOSHIPPING'                     => 1,
            'RETURNURL'                      => $data['return_url'],
            'CANCELURL'                      => $data['cancel_url'],
            'LOCALE'                         => $this->locale,
        ]);

        $this->setTaxAmount($data);

        $this->setShippingAmount($data);

        $this->setShippingDiscount($data);

        $this->setTaxAmount($data);

        $this->setExpressCheckoutRecurringPaymentConfig($data, $subscription);

        $response = $this->doPayPalRequest('SetExpressCheckout');

        return collect($response)->merge([
            'paypal_link' => !empty($response['TOKEN']) ? $this->config['gateway_url'].'/webscr?cmd=_express-checkout&token='.$response['TOKEN'] : null,
        ])->toArray();
    }

    /**
     * Perform a GetExpressCheckoutDetails API call on PayPal.
     *
     * @param string $token
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function getExpressCheckoutDetails($token)
    {
        $this->setRequestData([
            'TOKEN' => $token,
        ]);

        return $this->doPayPalRequest('GetExpressCheckoutDetails');
    }

    /**
     * Perform DoExpressCheckoutPayment API call on PayPal.
     *
     * @param array  $data
     * @param string $token
     * @param string $payerId
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function doExpressCheckoutPayment($data, $token, $payerId)
    {
        $this->setItemSubTotal($data);

        $this->post = $this->setCartItems($data['items'])->merge([
            'TOKEN'                          => $token,
            'PAYERID'                        => $payerId,
            'PAYMENTREQUEST_0_ITEMAMT'       => $this->subtotal,
            'PAYMENTREQUEST_0_AMT'           => $data['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION' => !empty($this->config['payment_action']) ? $this->config['payment_action'] : 'Sale',
            'PAYMENTREQUEST_0_CURRENCYCODE'  => $this->currency,
            'PAYMENTREQUEST_0_DESC'          => $data['invoice_description'],
            'PAYMENTREQUEST_0_INVNUM'        => $data['invoice_id'],
            'PAYMENTREQUEST_0_NOTIFYURL'     => $this->notifyUrl,
        ]);

        $this->setTaxAmount($data);

        $this->setShippingAmount($data);

        return $this->doPayPalRequest('DoExpressCheckoutPayment');
    }

    /**
     * Perform a DoAuthorization API call on PayPal.
     *
     * @param string $authorizationId Transaction ID
     * @param float  $amount          Amount to capture
     * @param array  $data            Optional request fields
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function doAuthorization($authorizationId, $amount, $data = [])
    {
        $this->setRequestData(
            array_merge($data, [
                'AUTHORIZATIONID' => $authorizationId,
                'AMT'             => $amount,
            ])
        );

        return $this->doPayPalRequest('DoAuthorization');
    }

    /**
     * Perform a DoCapture API call on PayPal.
     *
     * @param string $authorizationId Transaction ID
     * @param float  $amount          Amount to capture
     * @param string $complete        Indicates whether or not this is the last capture.
     * @param array  $data            Optional request fields
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function doCapture($authorizationId, $amount, $complete = 'Complete', $data = [])
    {
        $this->setRequestData(
            array_merge($data, [
                'AUTHORIZATIONID' => $authorizationId,
                'AMT'             => $amount,
                'COMPLETETYPE'    => $complete,
                'CURRENCYCODE'    => $this->currency,
            ])
        );

        return $this->doPayPalRequest('DoCapture');
    }

    /**
     * Perform a DoReauthorization API call on PayPal to reauthorize an existing authorization transaction.
     *
     * @param string $authorizationId
     * @param float  $amount
     * @param array  $data
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function doReAuthorization($authorizationId, $amount, $data = [])
    {
        $this->setRequestData(
            array_merge($data, [
                'AUTHORIZATIONID' => $authorizationId,
                'AMOUNT'          => $amount,
            ])
        );

        return $this->doPayPalRequest('DoReauthorization');
    }

    /**
     * Perform a DoVoid API call on PayPal.
     *
     * @param string $authorizationId Transaction ID
     * @param array  $data            Optional request fields
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function doVoid($authorizationId, $data = [])
    {
        $this->setRequestData(
            array_merge($data, [
                'AUTHORIZATIONID' => $authorizationId,
            ])
        );

        return $this->doPayPalRequest('DoVoid');
    }

    /**
     * Perform a CreateBillingAgreement API call on PayPal.
     *
     * @param string $token
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function createBillingAgreement($token)
    {
        $this->setRequestData([
            'TOKEN' => $token,
        ]);

        return $this->doPayPalRequest('CreateBillingAgreement');
    }

    /**
     * Perform a CreateRecurringPaymentsProfile API call on PayPal.
     *
     * @param array  $data
     * @param string $token
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function createRecurringPaymentsProfile($data, $token)
    {
        $this->post = $this->setRequestData($data)->merge([
            'TOKEN' => $token,
        ]);

        return $this->doPayPalRequest('CreateRecurringPaymentsProfile');
    }

    /**
     * Perform a GetRecurringPaymentsProfileDetails API call on PayPal.
     *
     * @param string $id
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function getRecurringPaymentsProfileDetails($id)
    {
        $this->setRequestData([
            'PROFILEID' => $id,
        ]);

        return $this->doPayPalRequest('GetRecurringPaymentsProfileDetails');
    }

    /**
     * Perform a UpdateRecurringPaymentsProfile API call on PayPal.
     *
     * @param array  $data
     * @param string $id
     *
     * @throws Exception
     *
     * @return array|StreamInterface
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
     * @throws Exception
     *
     * @return array|StreamInterface
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
     * Perform a ManageRecurringPaymentsProfileStatus API call on PayPal to cancel a RecurringPaymentsProfile.
     *
     * @param string $id
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function cancelRecurringPaymentsProfile($id)
    {
        return $this->manageRecurringPaymentsProfileStatus($id, 'Cancel');
    }

    /**
     * Perform a ManageRecurringPaymentsProfileStatus API call on PayPal to suspend a RecurringPaymentsProfile.
     *
     * @param string $id
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function suspendRecurringPaymentsProfile($id)
    {
        return $this->manageRecurringPaymentsProfileStatus($id, 'Suspend');
    }

    /**
     * Perform a ManageRecurringPaymentsProfileStatus API call on PayPal to reactivate a RecurringPaymentsProfile.
     *
     * @param string $id
     *
     * @throws Exception
     *
     * @return array|StreamInterface
     */
    public function reactivateRecurringPaymentsProfile($id)
    {
        return $this->manageRecurringPaymentsProfileStatus($id, 'Reactivate');
    }
}
