<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait Identity
{
    /**
     * Get user profile information.
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/identity/v1/#userinfo_get
     */
    public function showProfileInfo()
    {
        $this->apiEndPoint = 'v1/identity/openidconnect/userinfo?schema=openid';

        $this->setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }
}
