<?php

namespace Rescue\Tests\Http;

use PHPUnit\Framework\TestCase;
use Rescue\Http\Factory\UriFactory;
use Rescue\Http\Request;
use Rescue\Http\Stream;

final class RequestTest extends TestCase
{
    public function testBase(): void
    {
        $uriFactory = new UriFactory();
        $uri = $uriFactory->createUri('/test');
        $request = new Request('post', $uri);
        $stream = new Stream();

        $request = $request->withBody($stream);
        $request = $request->withRequestTarget('origin-form');

        $this->assertEquals($uri, $request->getUri());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('origin-form', $request->getRequestTarget());
        $this->assertTrue($request->mayHaveABody());
        $this->assertEquals('', $request->getBody()->getContents());
    }
}
