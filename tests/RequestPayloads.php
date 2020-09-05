<?php

namespace Srmklive\PayPal\Tests;

trait RequestPayloads
{
    /**
     * @return array
     */
    protected function createProductParams()
    {
        return \GuzzleHttp\json_decode('{
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
    protected function updateProductParams()
    {
        return \GuzzleHttp\json_decode('[
          {
            "op": "replace",
            "path": "/description",
            "value": "Premium video streaming service"
          }
        ]', true);
    }
}
