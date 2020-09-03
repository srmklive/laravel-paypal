<?php

namespace Srmklive\PayPal\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;

class AdapterTest extends TestCase
{
    use MockClientClasses;

    /** @var \Srmklive\PayPal\Services\PayPal|\Prophecy\Prophecy\ObjectProphecy */
    protected $client;

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->createProphecyObject(PayPalClient::class);

        $this->assertInstanceOf(PayPalClient::class, $this->client->reveal());
    }
}
