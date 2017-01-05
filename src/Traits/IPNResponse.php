<?php

namespace Srmklive\PayPal\Traits;

use Illuminate\Http\Request;

trait IPNResponse
{
    use PayPalRequest;

    /**
     * Retrieve IPN Response From PayPal.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function parsePayPalIPN(Request $request)
    {
        $request->merge(['cmd' => '_notify-validate']);
        $post = $request->all();

        return $this->verifyIPN($post);
    }
}
