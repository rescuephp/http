<?php

declare(strict_types=1);

namespace Rescue\Tests\Http\Factory;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Rescue\Http\Factory\ServerRequestFactory;
use Rescue\Http\Factory\StreamFactory;
use Rescue\Http\Factory\UriFactory;

final class ServerRequestFactoryTest extends TestCase
{
    public function testBase(): void
    {
        $uriFactory = new UriFactory();
        $streamFactory = new StreamFactory();
        $factory = new ServerRequestFactory($uriFactory, $streamFactory);
        $request = $factory->createServerRequest('post', '/test');

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/test', $request->getUri()->getPath());
        $this->assertEquals('', $request->getBody()->getContents());
    }

    public function testFromArray(): void
    {
        $uriFactory = new UriFactory();
        $streamFactory = new StreamFactory();
        $factory = new ServerRequestFactory($uriFactory, $streamFactory);
        $request = $factory->createServerRequestFromArray([
            'SERVER_SOFTWARE' => 'PHP 7.1.19 Development Server',
            'SERVER_PROTOCOL' => 'HTTP/1.0',
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => '8000',
            'REQUEST_URI' => '/user/1234',
            'REQUEST_METHOD' => 'DELETE',
            'HTTP_HOST' => 'localhost:8000',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_CACHE_CONTROL' => 'max-age=0',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36',
            'HTTP_DNT' => '1',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, br',
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9,ru-RU;q=0.8,ru;q=0.7',
        ]);

        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('/user/1234', $request->getUri()->getPath());
        $this->assertEquals(8000, $request->getUri()->getPort());
        $this->assertEquals('localhost', $request->getUri()->getHost());
        $this->assertEquals('1.0', $request->getProtocolVersion());
        $this->assertEquals('', $request->getBody()->getContents());
        $this->assertEquals(['en-US,en;q=0.9,ru-RU;q=0.8,ru;q=0.7'], $request->getHeader('accept-language'));
    }

    public function testInvalidParams(): void
    {
        $uriFactory = new UriFactory();
        $streamFactory = new StreamFactory();
        $factory = new ServerRequestFactory($uriFactory, $streamFactory);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid SERVER_URI');

        $factory->createServerRequestFromArray([
            'REQUEST_METHOD' => 'GET',
        ]);
    }

    public function testInvalidHost(): void
    {
        $uriFactory = new UriFactory();
        $streamFactory = new StreamFactory();
        $factory = new ServerRequestFactory($uriFactory, $streamFactory);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid REQUEST_METHOD');

        $factory->createServerRequestFromArray([
            'REQUEST_URI' => '/',
        ]);
    }
}
