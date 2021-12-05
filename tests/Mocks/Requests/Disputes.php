<?php

namespace Srmklive\PayPal\Tests\Mocks\Requests;

trait Disputes
{
    /**
     * @return array
     */
    protected function updateDisputeParams()
    {
        return \GuzzleHttp\json_decode('[
  {
    "op": "add",
    "path": "/partner_actions/-",
    "value": {
      "id": "AMX-22345",
      "name": "ACCEPT_DISPUTE",
      "create_time": "2018-01-12T10:41:35.000Z",
      "status": "PENDING"
    }
  }
]', true);
    }
}
