<?php

namespace Rescue\Http;

class Response implements ResponseInterface
{
    use MessageTrait;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $reasonPhrase;

    public function __construct(int $statusCode = ResponseInterface::STATUS_OK, $reasonPhrase = '')
    {
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
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
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
