<?php

namespace Rescue\Tests\Http\Factory;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Rescue\Http\Factory\StreamFactory;
use Rescue\Http\Factory\UploadedFileFactory;
use Rescue\Http\UploadedFileInterface;
use function dirname;
use const UPLOAD_ERR_OK;

final class UploadedFileFactoryTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testBase(): void
    {
        $name = random_int(10000, 99999);
        $filename = dirname(__DIR__) . '/temp/' . $name;

        $handle = fopen($filename, 'wb+');
        fwrite($handle, 'test2');

        $streamFactory = new StreamFactory();
        $factory = new UploadedFileFactory($streamFactory);

        $uploadedFile = $factory->createUploadedFile(
            $handle,
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
        $name = random_int(10000, 99999);
        $filename = dirname(__DIR__) . '/temp/' . $name;

        $handle = fopen($filename, 'wb+');
        fwrite($handle, 'test24');

        $streamFactory = new StreamFactory();
        $factory = new UploadedFileFactory($streamFactory);

        $uploadedFile = $factory->createUploadedFile(
            $filename,
            6,
            UPLOAD_ERR_OK,
            $name,
            'text/plain'
        );

        $this->assertEquals(6, $uploadedFile->getSize());
        $this->assertEquals(UPLOAD_ERR_OK, $uploadedFile->getError());
        $this->assertEquals($name, $uploadedFile->getClientFilename());
        $this->assertEquals('text/plain', $uploadedFile->getClientMediaType());

        unlink($filename);
    }

    /**
     * @throws Exception
     */
    public function testInvalidFile(): void
    {
        $streamFactory = new StreamFactory();
        $factory = new UploadedFileFactory($streamFactory);

        $this->expectException(InvalidArgumentException::class);

        $factory->createUploadedFile(true, 5, 0, 'asdf', 'text/plain');
    }

    /**
     * @throws Exception
     */
    public function testFromArray(): void
    {
        $name = random_int(10000, 99999);
        $filename = dirname(__DIR__) . '/temp/' . $name;

        $handle = fopen($filename, 'wb+');
        fwrite($handle, '{"a":"c"}');

        $data = [
            'test' => [
                'name' => $name,
                'tmp_name' => $filename,
                'size' => 9,
                'type' => 'application/json',
            ],
        ];

        $streamFactory = new StreamFactory();
        $factory = new UploadedFileFactory($streamFactory);

        $uploadedFiles = $factory->createFromArray($data);

        $this->assertNotEmpty($uploadedFiles);

        $uploadedFile = array_shift($uploadedFiles);
        $this->assertInstanceOf(UploadedFileInterface::class, $uploadedFile);

        $this->assertEquals(9, $uploadedFile->getSize());
        $this->assertEquals(UPLOAD_ERR_OK, $uploadedFile->getError());
        $this->assertEquals($name, $uploadedFile->getClientFilename());
        $this->assertEquals('application/json', $uploadedFile->getClientMediaType());

        unlink($filename);
    }

    /**
     * @throws Exception
     */
    public function testFromArrayMultiple(): void
    {
        $name1 = random_int(10000, 99999);
        $filename1 = dirname(__DIR__) . '/temp/' . $name1;
        fopen($filename1, 'wb+');

        $name2 = random_int(10000, 99999);
        $filename2 = dirname(__DIR__) . '/temp/' . $name2;
        fopen($filename2, 'wb+');

        $data = [
            'test' => [
                'name' => [$name1, $name2],
                'tmp_name' => [$filename1, $filename2],
                'size' => [9, 5],
                'type' => ['application/json', 'image/png'],
            ],
        ];

        $streamFactory = new StreamFactory();
        $factory = new UploadedFileFactory($streamFactory);

        $uploadedFiles = $factory->createFromArray($data);

        $this->assertNotEmpty($uploadedFiles);

        $uploadedFile = array_pop($uploadedFiles);
        $this->assertInstanceOf(UploadedFileInterface::class, $uploadedFile);

        $this->assertEquals(5, $uploadedFile->getSize());
        $this->assertEquals(UPLOAD_ERR_OK, $uploadedFile->getError());
        $this->assertEquals($name2, $uploadedFile->getClientFilename());
        $this->assertEquals('image/png', $uploadedFile->getClientMediaType());

        unlink($filename1);
        unlink($filename2);
    }
}
