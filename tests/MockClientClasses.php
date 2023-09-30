<?php

namespace Srmklive\PayPal\Tests;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler as HttpMockHandler;
use GuzzleHttp\HandlerStack as HttpHandlerStack;
use GuzzleHttp\Psr7\Response as HttpResponse;
use GuzzleHttp\Psr7\Stream as HttpStream;
use Psr\Http\Message\ResponseInterface;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Traits\JsonEncodeDecodeSelector;

trait MockClientClasses
{
    use JsonEncodeDecodeSelector;

    private function mock_http_client($response): HttpClient
    {
        $mock = new HttpMockHandler([
            new HttpResponse(
                200,
                [],
                ($response === false) ? '' : $this->jsonEncodeFunction()($response)
            ),
        ]);

        $handler = HttpHandlerStack::create($mock);

        return new HttpClient(['handler' => $handler]);
    }

    private function mock_http_request($expectedResponse, $expectedEndpoint, $expectedParams, $expectedMethod = 'post')
    {
        $set_method_name = 'setMethods';
        if (strpos(phpversion(), '8.1') !== false || strpos(phpversion(), '8.2') !== false) {
            $set_method_name = 'onlyMethods';
        }

        $mockResponse = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
        $mockResponse->expects($this->exactly(1))
            ->method('getBody')
            ->willReturn(new HttpStream(fopen('data://text/plain,'.$expectedResponse, 'r')));

        $mockHttpClient = $this->getMockBuilder(HttpClient::class)
            ->{$set_method_name}([$expectedMethod])
            ->getMock();
        $mockHttpClient->expects($this->once())
            ->method($expectedMethod)
            ->with($expectedEndpoint, $expectedParams)
            ->willReturn($mockResponse);

        return $mockHttpClient;
    }

    private function mock_client($expectedResponse, $expectedMethod, $token = false, $additionalMethod = null)
    {
        $set_method_name = 'setMethods';

        $methods = [$expectedMethod, 'setApiCredentials'];
        $methods[] = ($token) ? 'getAccessToken' : '';
        $methods[] = $additionalMethod ?? '';

        $mockClient = $this->getMockBuilder(PayPalClient::class)
            ->{$set_method_name}(array_filter($methods))
            ->getMock();

        if ($token) {
            $mockClient->expects($this->exactly(1))
                ->method('getAccessToken');
        }

        if (isset($additionalMethod)) {
            $mockClient->expects($this->any())
            ->method($additionalMethod);
        }

        $mockClient->expects($this->exactly(1))
            ->method('setApiCredentials');

        $mockClient->expects($this->exactly(1))
            ->method('setApiCredentials');

        $mockClient->expects($this->exactly(1))
            ->method($expectedMethod)
            ->willReturn($expectedResponse);

        return $mockClient;
    }

    private function getMockCredentials(): array
    {
        return [
            'mode'    => 'sandbox',
            'sandbox' => [
                'client_id'     => 'some-client-id',
                'client_secret' => 'some-access-token',
                'app_id'        => 'some-app-id',
            ],
            'payment_action' => 'Sale',
            'currency'       => 'USD',
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   => true,
        ];
    }

    private function getApiCredentials(): array
    {
        return [
            'mode'    => 'sandbox',
            'sandbox' => [
                'client_id'     => 'AbJgVQM6g57qPrXimGkBz1UaBOXn1dKLSdUj7BgiB3JhzJRCapzCnkPq6ycOOmgXHtnDZcjwLMJ2IdAI',
                'client_secret' => 'EPd_XBNkfhU3-MlSw6gpa6EJj9x8QBdsC3o77jZZWjcFy_hrjR4kzBP8QN3MPPH4g52U_acG4-ogWUxI',
                'app_id'        => 'APP-80W284485P519543T',
            ],
            'payment_action' => 'Sale',
            'currency'       => 'USD',
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   => true,
        ];
    }
}
