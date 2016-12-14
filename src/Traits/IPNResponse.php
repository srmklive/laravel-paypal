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
     */
    public function postNotify(Request $request)
    {
        $post = $request->merge(['cmd' => '_notify-validate']);
        $response = $this->verifyIPN($post);

        session([
            'ipn' => $response,
        ]);
    }
}
