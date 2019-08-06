<?php

declare(strict_types=1);

namespace Rescue\Tests\Http;

use PHPUnit\Framework\TestCase;
use Rescue\Http\Response;
use Rescue\Http\Stream;

final class ResponseTest extends TestCase
{
    public function testBase(): void
    {
        $response = new Response();
        $stream = new Stream();

        $response = $response->withBody($stream);
        $response = $response->withStatus(404, 'Awwww');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Awwww', $response->getReasonPhrase());
        $this->assertEquals('', $response->getBody()->getContents());
    }
}
