<?php

declare(strict_types=1);

namespace Rescue\Tests\Http;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Rescue\Http\Stream;
use RuntimeException;

final class StreamTest extends TestCase
{
    public function testBase(): void
    {
        $file = fopen('php://temp', 'wb+');
        fwrite($file, 'test');

        $stream = new Stream($file);

        $this->assertEquals('test', (string)$stream);
        $this->assertEquals(4, $stream->tell());
        $this->assertTrue($stream->eof());
        $this->assertTrue($stream->isSeekable());
        $this->assertTrue($stream->isSeekable());
        $this->assertTrue($stream->isWritable());
        $this->assertTrue($stream->isReadable());
    }

    public function testInvalidConstructor(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Stream('test');
    }

    public function testDetachedStream(): void
    {
        $file = fopen('php://temp', 'wb+');
        fwrite($file, 'test');

        $stream = new Stream($file);
        $this->assertEquals($file, $stream->detach());
        $this->assertEquals('', (string)$stream);
        $this->assertNull($stream->detach());
        $this->assertEquals([], $stream->getMetadata());
        $this->assertNull($stream->getMetadata('test'));
        $this->assertNull($stream->getSize());
    }

    public function testGetContentsWithoutStream(): void
    {
        $stream = $this->getStreamDetached();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stream is detached');
        $stream->getContents();
    }

    private function getStreamDetached(): StreamInterface
    {
        $file = fopen('php://temp', 'wb+');
        fwrite($file, 'test');

        $stream = new Stream($file);
        $stream->detach();

        return $stream;
    }

    public function testReadWithoutStream(): void
    {
        $stream = $this->getStreamDetached();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stream is detached');
        $stream->read(1);
    }

    public function testWriteWithoutStream(): void
    {
        $stream = $this->getStreamDetached();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stream is detached');
        $stream->write('write');
    }

    public function testSeekWithoutStream(): void
    {
        $stream = $this->getStreamDetached();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stream is detached');
        $stream->seek(0);
    }

    public function testRewindWithoutStream(): void
    {
        $stream = $this->getStreamDetached();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stream is detached');
        $stream->rewind();
    }

    public function testEofWithoutStream(): void
    {
        $stream = $this->getStreamDetached();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stream is detached');
        $stream->eof();
    }

    public function testTellWithoutStream(): void
    {
        $stream = $this->getStreamDetached();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stream is detached');
        $stream->tell();
    }
}
