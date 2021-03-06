<?php

declare(strict_types=1);

namespace Rescue\Http;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

class Response implements ResponseInterface
{
    use MessageTrait;

    private int $statusCode;

    private string $reasonPhrase;

    public function __construct(
        int $statusCode = StatusCodeInterface::STATUS_OK,
        string $reasonPhrase = ''
    ) {
        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        $code = (int)$code;
        if ($this->statusCode === $code && $this->reasonPhrase === $reasonPhrase) {
            return $this;
        }

        $instance = clone $this;
        $instance->statusCode = $code;
        $instance->reasonPhrase = $reasonPhrase;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }
}
