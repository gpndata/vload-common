<?php
namespace Vload\CommonTest\Api;

use PHPUnit\Framework\TestCase;
use Vload\Common\Api\Client;
use Vload\Common\Api\Config;

class ConfigTest extends TestCase
{

    public function testInit()
    {
        $config = Config::init('secret', 'https://api.url/');
        $client = $config->getClient();
        $this->assertInstanceOf(Config::class, $config);
        $this->assertInstanceOf(Client::class, $client);

        $reflection = new \ReflectionObject($client);
        $secret = $reflection->getProperty('secret');
        $secret->setAccessible(true);
        $apiUrl = $reflection->getProperty('apiUrl');
        $apiUrl->setAccessible(true);
        $this->assertEquals('secret', $secret->getValue($client));
        $this->assertEquals('https://api.url/', $apiUrl->getValue($client));
    }

    public function testInitWithClient()
    {
        $client = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $config = Config::initWithClient($client);
        $this->assertInstanceOf(Config::class, $config);
        $this->assertSame($client, $config->getClient());
    }

    public function testInitWithInvalidClient()
    {
        $client = $this->getMockBuilder(\GuzzleHttp\Client::class)->disableOriginalConstructor()->getMock();
        $this->expectException(\InvalidArgumentException::class);
        Config::initWithClient($client);
    }
}
