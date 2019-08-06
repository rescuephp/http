<?php

declare(strict_types=1);

namespace Rescue\Http\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Rescue\Http\Response;
use Rescue\Http\StatusCode;

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
    public function createResponse(
        int $code = StatusCode::STATUS_OK,
        string $reasonPhrase = ''
    ): ResponseInterface {
        $stream = $this->streamFactory->createStream();

        return (new Response($code, $reasonPhrase))->withBody($stream);
    }
}
