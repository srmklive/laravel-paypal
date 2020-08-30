<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait Invoices
{
    /**
     * Generate the next invoice number.
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_generate-next-invoice-number
     */
    public function generateInvoiceNumber()
    {
        $this->apiEndPoint = 'v2/invoicing/generate-next-invoice-number';
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Create a new draft invoice.
     *
     * @param array $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_create
     */
    public function createInvoice(array $data)
    {
        $this->apiEndPoint = 'v2/invoicing/invoices';
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Get list of invoices.
     *
     * @param int   $page
     * @param int   $size
     * @param bool  $totals
     * @param array $fields
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_list
     */
    public function listInvoices($page = 1, $size = 20, $totals = true, array $fields = [])
    {
        $fields_list = collect($fields);

        $fields = '';
        if ($fields_list->count() > 0) {
            $fields = "&fields={$fields_list->implode(',')}";
        }

        $this->apiEndPoint = "v2/invoicing/invoices?page={$page}&page_size={$size}&total_required={$totals}{$fields}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Delete an invoice.
     *
     * @param string $invoice_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_list
     */
    public function deleteInvoice($invoice_id)
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'delete';

        return $this->doPayPalRequest();
    }

    /**
     * Update an existing invoice.
     *
     * @param string $invoice_id
     * @param array  $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_update
     */
    public function updateInvoice($invoice_id, array $data)
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->options['json'] = $data;

        $this->verb = 'put';

        return $this->doPayPalRequest();
    }

    /**
     * Show details for an existing invoice.
     *
     * @param string $invoice_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_get
     */
    public function showInvoiceDetails($invoice_id)
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Cancel an existing invoice which is already sent.
     *
     * @param string $invoice_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_cancel
     */
    public function cancelInvoice($invoice_id)
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}/cancel";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Generate QR code against an existing invoice.
     *
     * @param string $invoice_id
     * @param int    $width
     * @param int    $height
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_generate-qr-code
     */
    public function generateQRCodeInvoice($invoice_id, $width = 200, $height = 20)
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}/generate-qr-code";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->options['json'] = [
            'width'     => $width,
            'height'    => $height,
        ];
        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Register payment against an existing invoice.
     *
     * @param string $invoice_id
     * @param string $payment_id
     * @param string $payment_date
     * @param string $payment_method
     * @param string $payment_note
     * @param float  $amount
     * @param string $currency
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_payments
     */
    public function registerPaymentInvoice($invoice_id, $payment_id, $payment_date, $payment_method, $payment_note, $amount, $currency = '')
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}/payments";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        if (isset($currency)) {
            $this->setCurrency($currency);
        }

        $data = [
            'payment_id'    => $payment_id,
            'payment_date'  => $payment_date,
            'method'        => $payment_method,
            'note'          => $payment_note,
            'amount'        => [
                'currency'  => $this->currency,
                'value'     => $amount,
            ],
        ];

        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Delete payment against an existing invoice.
     *
     * @param string $invoice_id
     * @param string $transaction_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_payments-delete
     */
    public function deleteExternalPaymentInvoice($invoice_id, $transaction_id)
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}/payments/{$transaction_id}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'delete';

        return $this->doPayPalRequest();
    }

    /**
     * Register payment against an existing invoice.
     *
     * @param string $invoice_id
     * @param string $payment_date
     * @param string $payment_method
     * @param float  $amount
     * @param string $currency
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_refunds
     */
    public function refundInvoice($invoice_id, $payment_date, $payment_method, $amount, $currency = '')
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}/refunds";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        if (isset($currency)) {
            $this->setCurrency($currency);
        }

        $data = [
            'refund_date'   => $payment_date,
            'method'        => $payment_method,
            'amount'        => [
                'currency'  => $this->currency,
                'value'     => $amount,
            ],
        ];

        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Delete refund against an existing invoice.
     *
     * @param string $invoice_id
     * @param string $transaction_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_refunds-delete
     */
    public function deleteRefundInvoice($invoice_id, $transaction_id)
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}/refunds/{$transaction_id}";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->verb = 'delete';

        return $this->doPayPalRequest();
    }

    /**
     * Send an existing invoice.
     *
     * @param string $invoice_id
     * @param string $subject
     * @param string $note
     * @param bool   $send_recipient
     * @param bool   $send_merchant
     * @param array  $recipients
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_send
     */
    public function sendInvoice($invoice_id, $subject = '', $note = '', $send_recipient = true, $send_merchant = false, array $recipients = [])
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}/send";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $data = [];
        if (!empty($subject)) {
            $data['subject'] = $subject;
        }

        if (!empty($note)) {
            $data['note'] = $note;
        }

        if (collect($recipients)->count() > 0) {
            $data['additional_recipients'] = $recipients;
        }

        $data['send_to_recipient'] = $send_recipient;
        $data['send_to_invoicer'] = $send_merchant;

        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Send reminder for an existing invoice.
     *
     * @param string $invoice_id
     * @param string $subject
     * @param string $note
     * @param bool   $send_recipient
     * @param bool   $send_merchant
     * @param array  $recipients
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#invoices_remind
     */
    public function sendInvoiceReminder($invoice_id, $subject = '', $note = '', $send_recipient = true, $send_merchant = false, array $recipients = [])
    {
        $this->apiEndPoint = "v2/invoicing/invoices/{$invoice_id}/remind";
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $data = [];
        if (!empty($subject)) {
            $data['subject'] = $subject;
        }

        if (!empty($note)) {
            $data['note'] = $note;
        }

        if (collect($recipients)->count() > 0) {
            $data['additional_recipients'] = $recipients;
        }

        $data['send_to_recipient'] = $send_recipient;
        $data['send_to_invoicer'] = $send_merchant;

        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
