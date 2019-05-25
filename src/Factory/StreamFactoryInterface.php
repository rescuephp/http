<?php

namespace Rescue\Http\Factory;

use Rescue\Http\StreamInterface;

interface StreamFactoryInterface
{
    /**
     * Create a new stream from a string.
     *
     * The stream SHOULD be created with a temporary resource.
     *
     * @param string $content
     *
     * @return StreamInterface
     */
    public function createStream(string $content = ''): StreamInterface;

    /**
     * Create a stream from an existing file.
     *
     * The file MUST be opened using the given mode, which may be any mode
     * supported by the `fopen` function.
     *
     * The `$filename` MAY be any string supported by `fopen()`.
     *
     * @param string $filename
     * @param string $mode
     *
     * @return StreamInterface
     */
    public function createStreamFromFile(string $filename, string $mode = 'rb'): StreamInterface;

    /**
     * Create a new stream from an existing resource.
     *
     * The stream MUST be readable and may be writable.
     *
     * @param resource $resource
     *
     * @return StreamInterface
     */
    public function createStreamFromResource($resource): StreamInterface;
}
