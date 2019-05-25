<?php

namespace Rescue\Http;


use function in_array;

class Request implements RequestInterface
{
    use MessageTrait;

    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $requestTarget;

    /**
     * Request constructor.
     * @param string $method
     * @param UriInterface $uri
     * @param string[][]|string[] $headers
     * @param StreamInterface|null $body
     * @param string $protocolVersion
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        array $headers = [],
        StreamInterface $body = null,
        string $protocolVersion = '1.1'
    ) {
        $this->method = $this->normalizeMethod($method);
        $this->uri = $uri;
        $this->setHeaders($headers);
        $this->body = $body;
        $this->protocolVersion = $protocolVersion;
    }

    private function normalizeMethod(string $method): string
    {
        return strtoupper($method);
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        if ($this->requestTarget !== null) {
            return $this->requestTarget;
        }

        return $this->composeRequestTarget();
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget): RequestInterface
    {
        if ($this->requestTarget === $requestTarget) {
            return $this;
        }

        $instance = clone $this;
        $instance->requestTarget = $requestTarget;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): RequestInterface
    {
        $normalized = $this->normalizeMethod($method);

        if ($this->method === $normalized) {
            return $this;
        }

        $instance = clone $this;
        $instance->method = $normalized;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        if ($this->uri === $uri) {
            return $this;
        }

        $instance = clone $this;
        $instance->uri = $uri;

        $headersNames = array_keys($this->headers);

        if ($preserveHost === false && !isset($headersNames['host']) && $uri->getHost() !== '') {
            $this->withHeader('host', $uri->getHost());
        }

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function mayHaveABody(): bool
    {
        return in_array($this->method, self::getMethodsWithBody(), true);
    }

    /**
     * @return string[]
     */
    public static function getMethodsWithBody(): array
    {
        return [
            self::METHOD_DELETE,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_PATCH,
        ];
    }

    private function composeRequestTarget(): string
    {
        $target = $this->uri->getPath();

        if ($target === '') {
            $target = '/';
        }

        if ($this->uri->getQuery() !== '') {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }
}
