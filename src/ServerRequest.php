<?php

declare(strict_types=1);

namespace Rescue\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
    private array $cookies = [];

    private array $query = [];

    /**
     * @var UploadedFileInterface[]
     */
    private array $uploadedFiles = [];

    private array $attributes = [];

    private array $serverParams;

    private ?array $parsedBody;

    /**
     * @inheritDoc
     * @params array $serverParams
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        array $headers = [],
        StreamInterface $body = null,
        string $protocolVersion = '1.1',
        array $serverParams = []
    ) {
        $this->serverParams = $serverParams;

        parent::__construct($method, $uri, $headers, $body, $protocolVersion);
    }

    /**
     * @inheritDoc
     */
    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams(): array
    {
        return $this->cookies;
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $instance = clone $this;
        $instance->cookies = $cookies;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams(): array
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParam(string $param, $default = null)
    {
        return $this->query[$param] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        $instance = clone $this;
        $instance->query = $query;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $instance = clone $this;
        $instance->uploadedFiles = $uploadedFiles;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBodyParam(string $param, $default = null)
    {
        return $this->parsedBody[$param] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        $instance = clone $this;
        $instance->parsedBody = $data;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[(string)$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function withAttribute($name, $value): ServerRequestInterface
    {
        $instance = clone $this;
        $instance->attributes[(string)$name] = $value;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute($name): ServerRequestInterface
    {
        $name = (string)$name;
        if (!isset($this->attributes[$name])) {
            return $this;
        }

        $instance = clone $this;
        unset($instance->attributes[$name]);

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withServerParams(array $server): ServerRequestInterface
    {
        $instance = clone $this;
        $instance->serverParams = $server;

        return $instance;
    }
}
