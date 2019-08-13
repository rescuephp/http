<?php

declare(strict_types=1);

namespace Rescue\Tests\Http;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Rescue\Http\Factory\StreamFactory;
use Rescue\Http\MessageTrait;
use Rescue\Http\Stream;

final class MessageTraitTest extends TestCase
{
    public function testBase(): void
    {
        $streamFactory = new StreamFactory();
        $stream = $streamFactory->createStream('test');

        $message = $message = $this->getMessage();

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
        $message = $this->getMessage();

        $message = $message->withAddedHeader('test', 'no/no');

        $this->assertEquals('no/no', $message->getHeaderLine('test'));

        $message = $message->withAddedHeader('test', 'no/no');

        $this->assertEquals('no/no; no/no', $message->getHeaderLine('test'));
    }

    public function testEmptyHeader(): void
    {
        $message = $this->getMessage();
        $this->assertEmpty($message->getHeader('test'));
    }

    public function testNotChange(): void
    {
        $messageOriginal = $this->getMessage();
        $stream = new Stream();
        $messageNewInstance = $messageOriginal->withBody($stream);
        $this->assertNotEquals($messageOriginal, $messageNewInstance);
        $messageNewInstance2 = $messageNewInstance->withBody($stream);
        $this->assertEquals($messageNewInstance2, $messageNewInstance);


        $messageOriginal = $this->getMessage();
        $messageNewInstance = $messageOriginal->withProtocolVersion('1.2');
        $this->assertNotEquals($messageOriginal, $messageNewInstance);
        $messageNewInstance2 = $messageNewInstance->withProtocolVersion('1.2');
        $this->assertEquals($messageNewInstance2, $messageNewInstance);

        $messageOriginal = $this->getMessage();
        $messageNewInstance = $messageOriginal->withHeader('test', 'afdadg');
        $this->assertNotEquals($messageOriginal, $messageNewInstance);
        $messageNewInstance2 = $messageNewInstance->withoutHeader('tesafa');
        $this->assertEquals($messageNewInstance, $messageNewInstance2);;
    }

    public function testWithSameHeader(): void
    {
        $messageOriginal = $this->getMessage();
        $messageNewInstance = $messageOriginal->withHeader('test', '1');
        $this->assertNotEquals($messageOriginal, $messageNewInstance);
        $messageNewInstance2 = $messageNewInstance->withHeader('test', '2');
        $this->assertNotEquals($messageNewInstance, $messageNewInstance2);
    }

    private function getMessage(): MessageInterface
    {
        return new class implements MessageInterface
        {
            use MessageTrait;
        };
    }
}
