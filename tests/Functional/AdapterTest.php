<?php

namespace Srmklive\PayPal\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy as AdapterProphecyObject;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Tests\MockClientClasses;

class AdapterTest extends TestCase
{
    use MockClientClasses;

    /** @var \Srmklive\PayPal\Services\PayPal|\Prophecy\Prophecy\ObjectProphecy */
    protected $client;

    public function setUp(): void
    {
        $this->client = $this->prophesize(PayPalClient::class);
        $this->client->setApiCredentials($this->getCredentials());

        parent::setUp();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(AdapterProphecyObject::class, $this->client);
    }
}
