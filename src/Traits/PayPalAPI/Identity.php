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
        $this->apiEndPoint = 'v1/identity/oauth2/userinfo?schema=paypalv1.1';

        $this->verb = 'get';

        return $this->doPayPalRequest();
    }

    /**
     * Create a merchant application.
     *
     * @param string $client_name
     * @param array  $redirect_uris
     * @param array  $contacts
     * @param string $payer_id
     * @param string $migrated_app
     * @param string $application_type
     * @param string $logo_url
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/identity/v1/#applications_post
     */
    public function createMerchantApplication(string $client_name, array $redirect_uris, array $contacts, string $payer_id, string $migrated_app, string $application_type = 'web', string $logo_url = '')
    {
        $this->apiEndPoint = 'v1/identity/applications';

        $this->options['json'] = array_filter([
            'application_type'  => $application_type,
            'redirect_uris'     => $redirect_uris,
            'client_name'       => $client_name,
            'contacts'          => $contacts,
            'payer_id'          => $payer_id,
            'migrated_app'      => $migrated_app,
            'logo_uri'          => $logo_url,
        ]);

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Create a merchant application.
     *
     * @param array  $features
     * @param string $account_property
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/identity/v1/#account-settings_post
     */
    public function setAccountProperties(array $features, string $account_property = 'BRAINTREE_MERCHANT')
    {
        $this->apiEndPoint = 'v1/identity/account-settings';

        $this->options['json'] = [
            'account_property'  => $account_property,
            'features'          => $features,
        ];

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }

    /**
     * Create a merchant application.
     *
     * @param string $account_property
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/identity/v1/#account-settings_deactivate
     */
    public function disableAccountProperties(string $account_property = 'BRAINTREE_MERCHANT')
    {
        $this->apiEndPoint = 'v1/identity/account-settings/deactivate';

        $this->options['json'] = [
            'account_property'  => $account_property,
        ];

        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
