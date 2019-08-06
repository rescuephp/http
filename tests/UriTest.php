<?php

declare(strict_types=1);

namespace Rescue\Tests\Http;

use PHPUnit\Framework\TestCase;
use Rescue\Http\Uri;

final class UriTest extends TestCase
{
    public function testBase(): void
    {
        $uri = new Uri();
        $uri = $uri->withFragment('#a');
        $uri = $uri->withHost('localhost');
        $uri = $uri->withPath('/test');
        $uri = $uri->withPort(6060);
        $uri = $uri->withQuery('?test=1');
        $uri = $uri->withScheme('https');
        $uri = $uri->withUserInfo('user', 'password');

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
