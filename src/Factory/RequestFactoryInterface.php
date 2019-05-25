<?php

namespace Rescue\Http\Factory;

use Rescue\Http\RequestInterface;
use Rescue\Http\UriInterface;

interface RequestFactoryInterface
{
    /**
     * Create a new request.
     *
     * @param string $method
     * @param UriInterface|string $uri
     *
     * @return RequestInterface
     */
    public function createRequest(string $method, $uri): RequestInterface;
}
