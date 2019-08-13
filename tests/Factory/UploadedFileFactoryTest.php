<?php

declare(strict_types=1);

namespace Rescue\Tests\Http\Factory;

use Exception;
use PHPUnit\Framework\TestCase;
use Rescue\Http\Factory\StreamFactory;
use Rescue\Http\Factory\UploadedFileFactory;
use function dirname;
use const UPLOAD_ERR_OK;

final class UploadedFileFactoryTest extends TestCase
{
    /**
     * @var StreamFactory
     */
    private $streamFactory;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->streamFactory = new StreamFactory();
    }

    /**
     * @throws Exception
     */
    public function testBase(): void
    {
        $this->markTestSkipped('todo');
        $name = (string)random_int(10000, 99999);
        $filename = dirname(__DIR__) . '/temp/' . $name;

        $file = fopen($filename, 'wb+');
        fwrite($file, 'test2');

        $stream = $this->streamFactory->createStreamFromFile($filename);
        $factory = new UploadedFileFactory();

        $uploadedFile = $factory->createUploadedFile(
            $stream,
            5,
            UPLOAD_ERR_OK,
            $name,
            'text/plain'
        );

        $this->assertEquals(5, $uploadedFile->getSize());
        $this->assertEquals(UPLOAD_ERR_OK, $uploadedFile->getError());
        $this->assertEquals($name, $uploadedFile->getClientFilename());
        $this->assertEquals('text/plain', $uploadedFile->getClientMediaType());

        unlink($filename);
    }

    /**
     * @throws Exception
     */
    public function testFilenameString(): void
    {
        $this->markTestSkipped('todo');
        $stream = $this->streamFactory->createStreamFromFile('test.txt');
        $factory = new UploadedFileFactory();

        $uploadedFile = $factory->createUploadedFile(
            $stream,
            6,
            UPLOAD_ERR_OK,
            'test.txt',
            'text/plain'
        );

        $this->assertEquals(6, $uploadedFile->getSize());
        $this->assertEquals(UPLOAD_ERR_OK, $uploadedFile->getError());
        $this->assertEquals('test.txt', $uploadedFile->getClientFilename());
        $this->assertEquals('text/plain', $uploadedFile->getClientMediaType());
    }
}
