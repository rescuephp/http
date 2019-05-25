<?php

namespace Rescue\Http\Factory;

use InvalidArgumentException;
use Rescue\Http\ServerRequestInterface;
use Rescue\Http\UriInterface;

interface ServerRequestFactoryInterface
{
    /**
     * Create a new server request.
     *
     * @param string $method
     * @param UriInterface|string $uri
     *
     * @return ServerRequestInterface
     */
    public function createServerRequest(string $method, $uri): ServerRequestInterface;

    /**
     * Create a new server request from server variables.
     *
     * @param array $server Typically $_SERVER or similar structure.
     *
     * @return ServerRequestInterface
     *
     * @throws InvalidArgumentException
     *  If no valid method or URI can be determined.
     */
    public function createServerRequestFromArray(array $server): ServerRequestInterface;
}
