<?php

namespace Rescue\Http\Factory;

use Rescue\Http\Response;
use Rescue\Http\ResponseInterface;

class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    public function __construct(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    /**
     * @inheritDoc
     */
    public function createResponse(int $code = ResponseInterface::STATUS_OK): ResponseInterface
    {
        $stream = $this->streamFactory->createStream();

        return (new Response($code, '1.1'))
            ->withBody($stream);
    }
}
