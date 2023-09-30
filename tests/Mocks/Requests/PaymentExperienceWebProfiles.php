<?php

namespace Srmklive\PayPal\Tests\Mocks\Requests;

use GuzzleHttp\Utils;

trait PaymentExperienceWebProfiles
{
    /**
     * @return array
     */
    private function mockCreateWebProfileParams(): array
    {
        return $this->jsonDecodeFunction()('[
  {
    "id": "XP-GCUV-X35G-HNEY-5MJY",
    "name": "exampleProfile",
    "flow_config": {
      "landing_page_type": "billing",
      "bank_txn_pending_url": "https://example.com/flow_config/"
    },
    "input_fields": {
      "no_shipping": 1,
      "address_override": 1
    },
    "presentation": {
      "logo_image": "https://example.com/logo_image/"
    }
  },
  {
    "id": "XP-A88A-LYLW-8Y3X-E5ER",
    "name": "exampleProfile",
    "flow_config": {
      "landing_page_type": "billing",
      "bank_txn_pending_url": "https://example.com/flow_config/"
    },
    "input_fields": {
      "no_shipping": 1,
      "address_override": 1
    },
    "presentation": {
      "logo_image": "https://example.com/logo_image/"
    }
  },
  {
    "id": "XP-RFV4-PVD8-AGHJ-8E5J",
    "name": "exampleProfile",
    "flow_config": {
      "bank_txn_pending_url": "https://example.com/flow_config/"
    },
    "input_fields": {
      "no_shipping": 1,
      "address_override": 1
    },
    "presentation": {
      "logo_image": "https://example.com/logo_image/"
    }
  }
]', true);
    }

    /**
     * @return array
     */
    private function partiallyUpdateWebProfileParams(): array
    {
        return $this->jsonDecodeFunction()('[
  {
    "op": "add",
    "path": "/presentation/brand_name",
    "value": "new_brand_name"
  },
  {
    "op": "remove",
    "path": "/flow_config/landing_page_type"
  }
]', true);
    }

    /**
     * @return array
     */
    private function updateWebProfileParams(): array
    {
        return $this->jsonDecodeFunction()('{
  "name": "exampleProfile",
  "presentation": {
    "logo_image": "https://example.com/logo_image/"
  },
  "input_fields": {
    "no_shipping": 1,
    "address_override": 1
  },
  "flow_config": {
    "landing_page_type": "billing",
    "bank_txn_pending_url": "https://example.com/flow_config/"
  }
}', true);
    }
}
