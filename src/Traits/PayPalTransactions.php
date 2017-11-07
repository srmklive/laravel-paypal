<?php

namespace Srmklive\PayPal\Traits;

trait PayPalTransactions
{
    /**
     * Perform a GetTransactionDetails API call on PayPal.
     *
     * @param string $transaction
     *
     * @return array
     */
    public function getTransactionDetails($transaction)
    {
        $this->setRequestData([
            'TRANSACTIONID' => $transaction,
        ]);

        return $this->doPayPalRequest('GetTransactionDetails');
    }

    /**
     * Perform a DoReferenceTransaction API operation on PayPal.
     *
     * @param string $transaction
     * @param string $action
     * @param float  $amount
     *
     * @return array
     */
    public function doReferenceTransaction($transaction, $action, $amount = 0.00)
    {
        $this->setRequestData([
            'REFERENCEID'   => $transaction,
            'PAYMENTACTION' => $action,
            'AMT'           => $amount,
        ]);

        return $this->doPayPalRequest('DoReferenceTransaction');
    }

    /**
     * Refund PayPal Transaction.
     *
     * @param string $transaction
     * @param float  $amount
     *
     * @return array
     */
    public function refundTransaction($transaction, $amount = 0.00)
    {
        $this->setRequestData([
            'TRANSACTIONID' => $transaction,
        ]);

        if ($amount > 0) {
            $this->post = $this->post->merge([
                'REFUNDTYPE' => 'Partial',
                'AMT'        => $amount,
            ]);
        }

        return $this->doPayPalRequest('RefundTransaction');
    }

    /**
     * Search Transactions On PayPal.
     *
     * @param array $post
     *
     * @return array
     */
    public function searchTransactions($post)
    {
        $this->setRequestData($post);

        return $this->doPayPalRequest('TransactionSearch');
    }
}
