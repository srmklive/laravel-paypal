<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait InvoicesTemplates
{
    /**
     * Create a new invoice template.
     *
     * @param array $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#templates_create
     */
    public function createInvoiceTemplate(array $data)
    {
        $this->apiEndPoint = 'v2/invoicing/templates';

        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Get list of invoice templates.
     *
     * @param int    $page
     * @param int    $size
     * @param string $fields
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#templates_list
     */
    public function listInvoiceTemplates(int $page = 1, int $size = 20, string $fields = 'all')
    {
        $this->apiEndPoint = "v2/invoicing/templates?page={$page}&page_size={$size}&fields={$fields}";

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Delete an invoice template.
     *
     * @param string $template_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#templates_delete
     */
    public function deleteInvoiceTemplate(string $template_id)
    {
        $this->apiEndPoint = "v2/invoicing/templates/{$template_id}";

        $this->verb = 'delete';

        return $this->doPayPalRequest(false);
    }

    /**
     * Update an existing invoice template.
     *
     * @param string $template_id
     * @param array  $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#templates_update
     */
    public function updateInvoiceTemplate(string $template_id, array $data)
    {
        $this->apiEndPoint = "v2/invoicing/templates/{$template_id}";

        $this->options['json'] = $data;

        $this->verb = 'put';

        return $this->doPayPalRequest();
    }

    /**
     * Show details for an existing invoice.
     *
     * @param string $template_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/invoicing/v2/#templates_get
     */
    public function showInvoiceTemplateDetails(string $template_id)
    {
        $this->apiEndPoint = "v2/invoicing/templates/{$template_id}";

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }
}
