<?php

namespace Rescue\Http\Factory;

use Rescue\Http\Stream;
use Rescue\Http\StreamInterface;

class StreamFactory implements StreamFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createStream(string $content = ''): StreamInterface
    {
        $stream = new Stream();

        $stream->write($content);

        return $stream;
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromFile(string $filename, string $mode = 'rb'): StreamInterface
    {
        $resource = fopen($filename, $mode);

        return new Stream($resource);
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return new Stream($resource);
    }
}
