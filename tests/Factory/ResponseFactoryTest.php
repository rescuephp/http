<?php

namespace Rescue\Tests\Http\Factory;

use PHPUnit\Framework\TestCase;
use Rescue\Http\Factory\ResponseFactory;
use Rescue\Http\Factory\StreamFactory;

final class ResponseFactoryTest extends TestCase
{
    public function testBase(): void
    {
        $streamFactory = new StreamFactory();
        $factory = new ResponseFactory($streamFactory);

        $response = $factory->createResponse(201);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('', $response->getBody()->getContents());
        $this->assertEquals('1.1', $response->getProtocolVersion());
    }
}
