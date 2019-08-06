<?php

declare(strict_types=1);

namespace Rescue\Http;

use Psr\Http\Message\StreamInterface;

trait MessageTrait
{
    /**
     * @var string
     */
    private $protocolVersion = '1.1';

    /**
     * @var StreamInterface
     */
    private $body;

    /**
     * @var string[][]
     */
    private $headers = [];

    /**
     * @var string[]
     */
    private $headerNamesMap = [];

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version): self
    {
        if ($this->protocolVersion === $version) {
            return $this;
        }

        $instance = clone $this;
        $instance->protocolVersion = $version;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): self
    {
        if ($this->body === $body) {
            return $this;
        }

        $instance = clone $this;
        $instance->body = $body;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name): string
    {
        return implode('; ', $this->getHeader($name));
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name): array
    {
        $normalized = $this->normalizeHeaderName($name);

        if (!isset($this->headerNamesMap[$normalized])) {
            return [];
        }

        $header = $this->headerNamesMap[$normalized];

        return $this->headers[$header] ?? [];
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value): self
    {
        if (!is_array($value)) {
            $value = (array)$value;
        }

        $value = $this->normalizeHeaderValues($value);
        $normalized = $this->normalizeHeaderName($name);

        $instance = clone $this;
        if (isset($instance->headerNamesMap[$normalized])) {
            $header = $this->headerNamesMap[$normalized];
            $instance->headers[$header] = array_merge($this->headers[$header], $value);
        } else {
            $instance->headerNamesMap[$normalized] = $name;
            $instance->headers[$name] = $value;
        }

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name): bool
    {
        return isset($this->headerNamesMap[$this->normalizeHeaderName($name)]);
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value): self
    {
        if (!is_array($value)) {
            $value = (array)$value;
        }

        $value = $this->normalizeHeaderValues($value);
        $normalized = $this->normalizeHeaderName($name);

        $instance = clone $this;

        if (isset($instance->headerNamesMap[$normalized])) {
            unset($instance->headers[$instance->headerNamesMap[$normalized]]);
        }

        $instance->headerNamesMap[$normalized] = $name;
        $instance->headers[$name] = $value;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name): self
    {
        $normalized = $this->normalizeHeaderName($name);

        if (!isset($this->headerNamesMap[$normalized])) {
            return $this;
        }

        $header = $this->headerNamesMap[$normalized];

        $instance = clone $this;
        unset($instance->headers[$header], $this->headerNamesMap[$normalized]);

        return $instance;
    }

    /**
     * @param string[] $values
     * @return string[]
     */
    private function normalizeHeaderValues(array $values): array
    {
        return array_map(static function ($value) {
            return trim($value, " \t");
        }, $values);
    }

    private function normalizeHeaderName(string $name): string
    {
        return strtolower($name);
    }

    /**
     * @param string[][]|string[] $headers
     */
    private function setHeaders(array $headers): void
    {
        $this->headerNamesMap = $this->headers = [];

        foreach ($headers as $header => $value) {
            if (!is_array($value)) {
                $value = (array)$value;
            }

            $value = $this->normalizeHeaderValues($value);
            $normalized = $this->normalizeHeaderName($header);

            if (isset($this->headerNamesMap[$normalized])) {
                $header = $this->headerNamesMap[$normalized];
                $this->headers[$header] = array_merge(...$value);
            } else {
                $this->headerNamesMap[$normalized] = $header;
                $this->headers[$header] = $value;
            }
        }
    }
}
