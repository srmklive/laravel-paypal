<?php

namespace Srmklive\PayPal\Tests;


use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $client = new Client();

        $this->assertInstanceOf(Client::class, $client);
    }
}
