<?php

namespace Srmklive\PayPal\Services;

use Srmklive\PayPal\Traits\JsonEncodeDecodeSelector;

class Str extends \Illuminate\Support\Str
{
    use JsonEncodeDecodeSelector;

    /**
     * Determine if a given value is valid JSON.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isJson($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        if (function_exists('json_validate')) {
            return json_validate($value, 512);
        }

        try {
            (new Str())->jsonDecodeFunction()($value, true, 512, 4194304);
        } catch (\Exception $jsonException) {
            return false;
        }

        return true;
    }
}
