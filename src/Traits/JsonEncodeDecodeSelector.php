<?php

namespace Srmklive\PayPal\Traits;

trait JsonEncodeDecodeSelector
{
    /**
     * Decide the function to use for decoding json.
     *
     * @return string
     */
    protected function jsonEncodeFunction(): string
    {
        return class_exists(\GuzzleHttp\Utils::class) &&
        method_exists(\GuzzleHttp\Utils::class, 'jsonEncode') ?
            '\GuzzleHttp\Utils::jsonEncode' : '\GuzzleHttp\json_encode';
    }

    /**
     * Decide the function to use for decoding json.
     *
     * @return string
     */
    protected function jsonDecodeFunction(): string
    {
        return class_exists(\GuzzleHttp\Utils::class) &&
        method_exists(\GuzzleHttp\Utils::class, 'jsonDecode') ?
            '\GuzzleHttp\Utils::jsonDecode' : '\GuzzleHttp\json_decode';
    }
}
