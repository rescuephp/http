<?php

namespace Rescue\Tests\Http\Factory;

use PHPUnit\Framework\TestCase;
use Rescue\Http\Factory\UriFactory;

final class UriFactoryTest extends TestCase
{
    public function testBase(): void
    {
        $factory = new UriFactory();
        $uri = $factory->createUri('https://test/test');

        $this->assertEmpty($uri->getFragment());
        $this->assertEquals('test', $uri->getHost());
        $this->assertEquals('https', $uri->getScheme());
        $this->assertEquals('/test', $uri->getPath());
        $this->assertNull($uri->getPort());
        $this->assertEmpty($uri->getQuery());
        $this->assertEmpty($uri->getUserInfo());

        $this->assertEquals(
            'https://test/test',
            (string)$uri
        );

    }

    public function testFullUrl(): void
    {
        $factory = new UriFactory();
        $uri = $factory->createUri('https://user:password@localhost:6060/test?test=1#a');

        $this->assertEquals('a', $uri->getFragment());
        $this->assertEquals('localhost', $uri->getHost());
        $this->assertEquals('/test', $uri->getPath());
        $this->assertEquals(6060, $uri->getPort());
        $this->assertEquals('test=1', $uri->getQuery());
        $this->assertEquals('https', $uri->getScheme());
        $this->assertEquals('user:password', $uri->getUserInfo());
        $this->assertEquals('user:password@localhost:6060', $uri->getAuthority());
        $this->assertEquals(
            'https://user:password@localhost:6060/test?test=1#a',
            (string)$uri
        );
    }
}
