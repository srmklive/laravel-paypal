<?php

namespace Srmklive\PayPal\Traits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException as HttpBadResponseException;
use GuzzleHttp\Exception\ClientException as HttpClientException;
use GuzzleHttp\Exception\ServerException as HttpServerException;

trait PayPalHttpClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    public $client;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * Function to initialize Http Client.
     *
     * @return void
     */
    protected function setClient()
    {
        $this->client = new HttpClient([
            'curl' => $this->curlConfig,
        ]);
    }

    /**
     * Function to set Http Client configuration.
     *
     * @return void
     */
    protected function setHttpClientConfiguration()
    {
        $this->curlConfig = [
            CURLOPT_SSLVERSION     => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_SSL_VERIFYPEER => $this->credentials['validate_ssl'],
        ];

        // Initialize Http Client
        $this->setClient();
    }

    /**
     * Perform PayPal API request & return response.
     *
     * @throws \Exception
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    private function makeHttpRequest()
    {
        try {
            return $this->client->post($this->apiUrl, $this->params)->getBody();
        } catch (HttpClientException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (HttpServerException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        } catch (HttpBadResponseException $e) {
            throw new \Exception($e->getRequest().' '.$e->getResponse());
        }
    }

    /**
     * Function To Perform PayPal API Request.
     *
     * @throws \Exception
     *
     * @return array|\Psr\Http\Message\StreamInterface
     */
    public function sendRequest()
    {
        try {
            // Perform PayPal HTTP API request.
            $response = $this->makeHttpRequest();

            return \GuzzleHttp\json_decode($response, true);
        } catch (\Exception $e) {
            $message = collect($e->getTrace())->implode('\n');
        }

        return [
            'type'    => 'error',
            'message' => $message,
        ];
    }
}
