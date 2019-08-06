<?php

declare(strict_types=1);

namespace Rescue\Tests\Http;

use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use Rescue\Http\Factory\StreamFactory;
use Rescue\Http\Factory\UploadedFileFactory;
use Rescue\Http\ServerRequest;
use Rescue\Http\Stream;
use Rescue\Http\Uri;
use function dirname;

final class ServerRequestTest extends TestCase
{
    public function testBase(): void
    {
        $request = new ServerRequest('post', new Uri());
        $stream = new Stream();

        $request = $request->withBody($stream);
        $request = $request->withServerParams(['a6' => 'c']);
        $request = $request->withParsedBody(['a5' => 'b']);
        $request = $request->withCookieParams(['g3' => 'r']);
        $request = $request->withQueryParams(['s2' => 'w']);
        $request = $request->withAttribute('g1', 'x');
        $request = $request->withAttribute('g2', 'x2');

        $this->assertEquals(['a6' => 'c'], $request->getServerParams());
        $this->assertEquals(['a5' => 'b'], $request->getParsedBody());
        $this->assertEquals('b', $request->getParsedBodyParam('a5'));
        $this->assertEquals(['g3' => 'r'], $request->getCookieParams());
        $this->assertEquals(['s2' => 'w'], $request->getQueryParams());
        $this->assertEquals('w', $request->getQueryParam('s2'));
        $this->assertEquals('x', $request->getAttribute('g1'));
        $this->assertEquals(['g1' => 'x', 'g2' => 'x2'], $request->getAttributes());
        $request = $request->withoutAttribute('g1');
        $this->assertEquals('zzz', $request->getAttribute('g1', 'zzz'));
        $this->assertEquals('', $request->getBody()->getContents());
    }

    /**
     * @throws Exception
     */
    public function testWithUploadedFiles(): void
    {
        $name = (string)random_int(10000, 99999);
        $filename = dirname(__DIR__) . '/tests/temp/' . $name;
        fopen($filename, 'wb+');

        $streamFactory = new StreamFactory();
        $stream = $streamFactory->createStreamFromFile($filename);

        $uploadedFileFactory = new UploadedFileFactory();
        $request = new ServerRequest('post', new Uri());

        $request = $request->withUploadedFiles([
            $uploadedFileFactory->createUploadedFile($stream),
        ]);

        $this->assertNotEmpty($request->getUploadedFiles());

        $this->assertContainsOnlyInstancesOf(UploadedFileInterface::class, $request->getUploadedFiles());

        unlink($filename);
    }
}
