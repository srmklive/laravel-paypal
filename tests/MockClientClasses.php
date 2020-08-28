<?php

namespace Srmklive\PayPal\Tests;

use Psr\Http\Message\ResponseInterface;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

trait MockClientClasses
{
    private function mock_http_request($expectedResponse, $expectedEndpoint, $expectedParams, $expectedMethod = 'post')
    {
        $set_method_name = 'setMethods';
        if (function_exists('onlyMethods')) {
            $set_method_name = 'onlyMethods';
        }

        $mockResponse = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
        $mockResponse->expects($this->exactly(1))
            ->method('getBody')
            ->willReturn($expectedResponse);

        $mockHttpClient = $this->getMockBuilder(HttpClient::class)
            ->{$set_method_name}([$expectedMethod])
            ->getMock();
        $mockHttpClient->expects($this->once())
            ->method($expectedMethod)
            ->with($expectedEndpoint, $expectedParams)
            ->willReturn($mockResponse);

        return $mockHttpClient;
    }

    private function mock_client($expectedResponse, $expectedMethod, $expectedParams)
    {
        $set_method_name = 'setMethods';
        if (function_exists('onlyMethods')) {
            $set_method_name = 'onlyMethods';
        }

        $mockClient = $this->getMockBuilder(PayPalClient::class)
            ->setConstructorArgs($expectedParams)
            ->{$set_method_name}([$expectedMethod])
            ->getMock();
        $mockClient->expects($this->exactly(1))
            ->method($expectedMethod)
            ->willReturn($expectedResponse);

        return $mockClient;
    }
}
