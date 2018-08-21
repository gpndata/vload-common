<?php
namespace Vload\CommonTest\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Vload\Common\Api\Client;
use Vload\Common\Exception\CommunicationFailed;
use Vload\Common\Exception\Conflict;
use Vload\Common\Exception\InvalidInput;
use Vload\Common\Exception\NotFound;
use Vload\Common\Exception\Unauthorized;

class ClientTest extends TestCase
{
    /** @var GuzzleClient|MockObject */
    private $client;

    public function setUp()
    {
        $this->client = $this->getMockBuilder(GuzzleClient::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return Client
     */
    private function createInstance()
    {
        return new Client('secret', 'https://api.url', $this->client);
    }

    public function testClientInit()
    {
        $instance = new Client('secret');
        $reflection = new \ReflectionObject($instance);
        $property = $reflection->getProperty('guzzle');
        $property->setAccessible(true);
        $client = $property->getValue($instance);
        $this->assertInstanceOf(GuzzleClient::class, $client);
    }

    public function testPost()
    {
        $stream = $this->getMockBuilder(StreamInterface::class)->disableOriginalConstructor()->getMock();
        $stream->method('getContents')->willReturn('{"response":"OK"}');
        $response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();
        $response->method('getBody')->willReturn($stream);

        $this->client->method('__call')->willReturn($response);
        $client = $this->createInstance();
        $response = $client->post('uri', ['param1'=>1]);
        $this->assertEquals(['response'=>'OK'], $response);
    }

    public function testGet()
    {
        $stream = $this->getMockBuilder(StreamInterface::class)->disableOriginalConstructor()->getMock();
        $stream->method('getContents')->willReturn('{"response":"OK"}');
        $response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();
        $response->method('getBody')->willReturn($stream);

        $this->client->method('__call')->willReturn($response);
        $client = $this->createInstance();
        $response = $client->get('uri');
        $this->assertEquals(['response'=>'OK'], $response);
    }

    public function testDelete()
    {
        $stream = $this->getMockBuilder(StreamInterface::class)->disableOriginalConstructor()->getMock();
        $stream->method('getContents')->willReturn('');
        $response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();
        $response->method('getBody')->willReturn($stream);

        $this->client->method('__call')->willReturn($response);
        $client = $this->createInstance();
        $response = $client->delete('uri');
        $this->assertEquals([], $response);
    }

    /**
     * @dataProvider errorProvider
     */
    public function testPostExceptions($mockExceptionHttpCode, $expectedExceptionClass, $validBody)
    {
        $exception = $this->createMockException($mockExceptionHttpCode, $validBody);
        $this->client->method('__call')->willThrowException($exception);

        $this->expectException($expectedExceptionClass);

        $client = $this->createInstance();
        $client->post('uri', []);
    }

    /**
     * @dataProvider errorProvider
     */
    public function testGetExceptions($mockExceptionHttpCode, $expectedExceptionClass, $validBody)
    {
        $exception = $this->createMockException($mockExceptionHttpCode, $validBody);
        $this->client->method('__call')->willThrowException($exception);

        $this->expectException($expectedExceptionClass);

        $client = $this->createInstance();
        $client->get('uri');
    }

    /**
     * @dataProvider errorProvider
     */
    public function testDeleteExceptions($mockExceptionHttpCode, $expectedExceptionClass, $validBody)
    {
        $exception = $this->createMockException($mockExceptionHttpCode, $validBody);
        $this->client->method('__call')->willThrowException($exception);

        $this->expectException($expectedExceptionClass);

        $client = $this->createInstance();
        $client->delete('uri');
    }

    private function createMockException($httpCode, $validBody)
    {
        $stream = $this->getMockBuilder(StreamInterface::class)->disableOriginalConstructor()->getMock();
        if ($validBody) {
            $stream->method('getContents')->willReturn('{"message":"Error message", "code": 0}');
        }
        /** @var RequestInterface|MockObject $request */
        $request = $this->getMockBuilder(RequestInterface::class)->disableOriginalConstructor()->getMock();
        /** @var ResponseInterface|MockObject $response */
        $response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();
        $response->method('getStatusCode')->willReturn($httpCode);
        $response->method('getBody')->willReturn($stream);
        return new RequestException('', $request, $response);
    }

    public function errorProvider()
    {
        return [
            [
                '400',
                InvalidInput::class,
                true,
            ],
            [
                '401',
                Unauthorized::class,
                true,
            ],
            [
                '404',
                NotFound::class,
                true,
            ],
            [
                '409',
                Conflict::class,
                true,
            ],
            [
                '500',
                CommunicationFailed::class,
                true,
            ],
            [
                '500',
                CommunicationFailed::class,
                false,
            ],
        ];
    }
}
