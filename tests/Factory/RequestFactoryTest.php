<?php

declare(strict_types=1);

namespace Rescue\Tests\Http\Factory;

use PHPUnit\Framework\TestCase;
use Rescue\Http\Factory\RequestFactory;
use Rescue\Http\Factory\UriFactory;

final class RequestFactoryTest extends TestCase
{
    public function testBase(): void
    {
        $uriFactory = new UriFactory();
        $factory = new RequestFactory($uriFactory);

        $request = $factory->createRequest('post', '/');

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/', $request->getUri()->getPath());
    }

    public function testWithUri(): void
    {
        $uriFactory = new UriFactory();
        $factory = new RequestFactory($uriFactory);
        $uri = $uriFactory->createUri('/test');

        $request = $factory->createRequest('delete', $uri);

        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('/test', $request->getUri()->getPath());
    }
}
