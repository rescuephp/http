<?php

declare(strict_types=1);

namespace Rescue\Tests\Http;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Rescue\Http\Factory\StreamFactory;
use Rescue\Http\MessageTrait;

final class MessageTraitTest extends TestCase
{
    public function testBase(): void
    {
        $streamFactory = new StreamFactory();
        $stream = $streamFactory->createStream('test');

        /** @var MessageInterface $message */
        $message = new class
        {
            use MessageTrait;
        };

        $message = $message->withBody($stream);
        $message = $message->withProtocolVersion('1.5');
        $message = $message->withHeader('User-Agent', 'test');
        $message = $message->withHeader('content-Type', 'plain/text');
        $message = $message->withAddedHeader('content-type', ['application/json']);
        $message = $message->withAddedHeader('content-type', ['a/test', 'a/test2']);

        $this->assertEquals('1.5', $message->getProtocolVersion());
        $this->assertEquals($stream, $message->getBody());
        $this->assertTrue($message->hasHeader('content-type'));
        $this->assertTrue($message->hasHeader('user-agent'));
        $this->assertEquals([
            'plain/text',
            'application/json',
            'a/test',
            'a/test2',
        ], $message->getHeader('content-type'));

        $this->assertEquals(['test'], $message->getHeader('user-agent'));
        $this->assertEquals([
            'User-Agent' => [
                'test',
            ],
            'content-Type' => [
                'plain/text',
                'application/json',
                'a/test',
                'a/test2',
            ],
        ], $message->getHeaders());

        $this->assertEquals(
            'plain/text; application/json; a/test; a/test2',
            $message->getHeaderLine('content-type')
        );

        $message = $message->withAddedHeader('user-agent', 'test2');

        $this->assertEquals('test; test2', $message->getHeaderLine('user-agent'));

        $message = $message->withoutHeader('content-type');

        $this->assertEmpty($message->getHeader('content-type'));
    }

    public function testWithAddedHeader(): void
    {
        /** @var MessageInterface $message */
        $message = new class
        {
            use MessageTrait;
        };

        $message = $message->withAddedHeader('test', 'no/no');

        $this->assertEquals('no/no', $message->getHeaderLine('test'));

        $message = $message->withAddedHeader('test', 'no/no');

        $this->assertEquals('no/no; no/no', $message->getHeaderLine('test'));
    }
}
