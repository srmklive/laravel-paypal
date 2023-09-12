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

    /**
     * List Users.
     *
     * @param string $field
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/identity/v2/#users_list
     */
    public function listUsers(string $field = 'userName')
    {
        $this->apiEndPoint = "v2/scim/Users?filter={$field}";

        $this->setRequestHeader('Content-Type', 'application/scim+json');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Show details for a user by ID.
     *
     * @param string $user_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/identity/v2/#users_get
     */
    public function showUserDetails(string $user_id)
    {
        $this->apiEndPoint = "v2/scim/Users/{$user_id}";

        $this->setRequestHeader('Content-Type', 'application/scim+json');

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Delete a user by ID.
     *
     * @param string $user_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/identity/v2/#users_get
     */
    public function deleteUser(string $user_id)
    {
        $this->apiEndPoint = "v2/scim/Users/{$user_id}";

        $this->setRequestHeader('Content-Type', 'application/scim+json');

        $this->verb = 'delete';

        return $this->doPayPalRequest(false);
    }
}
