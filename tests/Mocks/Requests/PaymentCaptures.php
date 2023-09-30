<?php

namespace Srmklive\PayPal\Tests\Mocks\Requests;

trait PaymentCaptures
{
    /**
     * @return array
     */
    private function mockRefundCapturedPaymentParams(): array
    {
        return $this->jsonDecodeFunction()('{
  "amount": {
    "value": "10.99",
    "currency_code": "USD"
  },
  "invoice_id": "INVOICE-123",
  "note_to_payer": "Defective product"
}', true);
    }
}
