<?php

namespace Rescue\Tests\Http;

use Exception;
use PHPUnit\Framework\TestCase;
use Rescue\Http\Stream;
use Rescue\Http\UploadedFile;
use function dirname;

final class UploadedFileTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testBase(): void
    {
        $name = random_int(10000, 99999);
        $filename = dirname(__DIR__) . '/tests/temp/' . $name;
        $moveToFilename = $filename . '_moved';

        $handle = fopen($filename, 'wb+');
        fwrite($handle, 'test');

        $stream = new Stream($handle);

        $uploadedFile = new UploadedFile(
            $stream,
            $stream->getSize(),
            0,
            $name,
            'text/plain'
        );

        $uploadedFile->moveTo($moveToFilename);

        $this->assertEquals($stream, $uploadedFile->getStream());
        $this->assertEquals($stream->getSize(), $uploadedFile->getSize());
        $this->assertEquals(0, $uploadedFile->getError());
        $this->assertEquals($name, $uploadedFile->getClientFilename());
        $this->assertEquals('text/plain', $uploadedFile->getClientMediaType());
        $this->assertTrue($uploadedFile->isMoved());

        unlink($moveToFilename);
    }
}
