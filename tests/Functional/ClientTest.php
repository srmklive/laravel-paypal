<?php

namespace Srmklive\PayPal\Tests\Functional;

use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy as ClientProphecyObject;
use Srmklive\PayPal\Tests\MockClientClasses;

class ClientTest extends TestCase
{
    use MockClientClasses;

    /** @var \GuzzleHttp\Client|\Prophecy\Prophecy\ObjectProphecy */
    protected $client;

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->createProphecyObject(HttpClient::class);

        $this->assertInstanceOf(ClientProphecyObject::class, $this->client);
    }
}
