<?php

namespace Rescue\Http\Factory;

use Rescue\Http\Request;
use Rescue\Http\RequestInterface;
use Rescue\Http\UriInterface;

class RequestFactory implements RequestFactoryInterface
{
    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    public function __construct(UriFactoryInterface $uriFactory)
    {
        $this->uriFactory = $uriFactory;
    }

    /**
     * @inheritDoc
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        if (!$uri instanceof UriInterface) {
            $uri = $this->uriFactory->createUri($uri);
        }

        return new Request($method, $uri);
    }
}
