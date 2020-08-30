<?php

namespace Srmklive\PayPal\Traits;

trait PayPalAPI
{
    use PayPalAPI\Trackers;
    use PayPalAPI\CatalogProducts;

    /**
     * Login through PayPal API to get access token.
     *
     * @throws \Throwable
     *
     * @return array|\Psr\Http\Message\StreamInterface|string
     *
     * @see https://developer.paypal.com/docs/api/get-an-access-token-curl/
     * @see https://developer.paypal.com/docs/api/get-an-access-token-postman/
     */
    public function getAccessToken()
    {
        $this->apiEndPoint = 'v1/oauth2/token';
        $this->apiUrl = collect([$this->apiUrl, $this->apiEndPoint])->implode('/');

        $this->options['auth'] = [$this->config['client_id'], $this->config['client_secret']];
        $this->options[$this->httpBodyParam] = [
            'grant_type' => 'client_credentials',
        ];

        $response = $this->doPayPalRequest();

        if (isset($response['access_token'])) {
            $this->access_token = $response['access_token'];

            if (empty($this->config['app_id'])) {
                $this->config['app_id'] = $response['app_id'];
            }

            $this->options['headers']['Authorization'] = "{$response['token_type']} {$this->access_token}";
        }

        return $response;
    }
}
