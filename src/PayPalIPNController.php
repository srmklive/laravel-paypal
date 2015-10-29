<?php namespace Srmklive\PayPal;

/**
 * Demo code to Handle IPN(Instant Payment Notification) from PayPal.
 */

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session;

class PayPalIPNController extends Controller {

    use PayPalRequestTrait;

    /**
     * Retrieve IPN Response From PayPal
     *
     * @param Request $request
     */
    public function postNotify(Request $request)
    {
        $post = [];
        $request_params = $request->all();

        foreach($request_params as $key=>$value)
            $post[$key] = $value;

        $post['cmd'] = '_notify-validate';

        $response = self::verifyIPN($post);

        Session::put('paypal_ipn_response', $response);
    }

}
