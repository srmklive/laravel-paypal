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
        $request->merge(['cmd' => '_notify-validate']);
        $post = $request->all();
        $response = $this->verifyIPN($post);

        session([
            'ipn' => $response,
        ]);
    }
}
