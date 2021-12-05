<?php

namespace Srmklive\PayPal\Tests\Mocks\Requests;

trait PaymentAuthorizations
{
    /**
     * @return array
     */
    private function mockCaptureAuthorizedPaymentParams()
    {
        return \GuzzleHttp\json_decode('{
  "amount": {
    "value": "10.99",
    "currency_code": "USD"
  },
  "invoice_id": "INVOICE-123",
  "note_to_payer": "Payment is due",
  "final_capture": true
}', true);
    }

    /**
     * @return array
     */
    private function mockReAuthorizeAuthorizedPaymentParams()
    {
        return \GuzzleHttp\json_decode('{
  "amount": {
    "value": "10.99",
    "currency_code": "USD"
  }
}', true);
    }
}
