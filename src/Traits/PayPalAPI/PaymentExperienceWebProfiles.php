<?php

namespace Srmklive\PayPal\Traits\PayPalAPI;

trait PaymentExperienceWebProfiles
{
    /**
     * List Web Experience Profiles.
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/payment-experience/v1/#web-profiles_get-list
     */
    public function listWebExperienceProfiles()
    {
        $this->apiEndPoint = 'v1/payment-experience/web-profiles';

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Create a Web Experience Profile.
     *
     * @param array  $data
     * @param string $request_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/payment-experience/v1/#web-profiles_get-list
     */
    public function createWebExperienceProfile(array $data, string $request_id)
    {
        $this->apiEndPoint = 'v1/payment-experience/web-profiles';

        $this->options['headers']['PayPal-Request-Id'] = $request_id;
        $this->options['json'] = $data;

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Delete a Web Experience Profile.
     *
     * @param string $profile_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/payment-experience/v1/#web-profiles_get-list
     */
    public function deleteWebExperienceProfile(string $profile_id)
    {
        $this->apiEndPoint = "v1/payment-experience/web-profiles/{$profile_id}";

        $this->verb = 'delete';

        return $this->doPayPalRequest();
    }

    /**
     * Partially update a Web Experience Profile.
     *
     * @param string $profile_id
     * @param array  $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/payment-experience/v1/#web-profiles_get-list
     */
    public function patchWebExperienceProfile(string $profile_id, array $data)
    {
        $this->apiEndPoint = "v1/payment-experience/web-profiles/{$profile_id}";

        $this->options['json'] = $data;

        $this->verb = 'patch';

        return $this->doPayPalRequest();
    }

    /**
     * Partially update a Web Experience Profile.
     *
     * @param string $profile_id
     * @param array  $data
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/payment-experience/v1/#web-profiles_get-list
     */
    public function updateWebExperienceProfile(string $profile_id, array $data)
    {
        $this->apiEndPoint = "v1/payment-experience/web-profiles/{$profile_id}";

        $this->options['json'] = $data;

        $this->verb = 'put';

        return $this->doPayPalRequest();
    }

    /**
     * Delete a Web Experience Profile.
     *
     * @param string $profile_id
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/payment-experience/v1/#web-profiles_get-list
     */
    public function showWebExperienceProfileDetails(string $profile_id)
    {
        $this->apiEndPoint = "v1/payment-experience/web-profiles/{$profile_id}";

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }
}
