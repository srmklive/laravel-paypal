<?php

namespace Srmklive\PayPal\Tests\Mocks\Requests;

trait CatalogProducts
{
    /**
     * @return array
     */
    private function createProductParams(): array
    {
        return $this->jsonDecodeFunction()('{
          "name": "Video Streaming Service",
          "description": "Video streaming service",
          "type": "SERVICE",
          "category": "SOFTWARE",
          "image_url": "https://example.com/streaming.jpg",
          "home_url": "https://example.com/home"
        }', true);
    }

    /**
     * @return array
     */
    private function updateProductParams(): array
    {
        return $this->jsonDecodeFunction()('[
          {
            "op": "replace",
            "path": "/description",
            "value": "Premium video streaming service"
          }
        ]', true);
    }
}
