<?php

namespace Srmklive\PayPal\Traits;

trait JsonDecodeSelector
{
    /**
     * Decide the function to use for decoding json.
     *
     * @return string
     */
    protected function jsonDecodeFunction(): string
    {
        return class_exists(\GuzzleHttp\Utils::class) ? '\GuzzleHttp\Utils::jsonDecode' : '\GuzzleHttp\Utils::json_decode';
    }
}
