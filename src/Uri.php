<?php

declare(strict_types=1);

namespace Rescue\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private string $path;

    private string $scheme;

    private string $userInfo = '';

    private string $host;

    private ?int $port;

    private string $query;

    private string $fragment;

    public function __construct(
        string $scheme = 'http',
        string $host = '',
        string $path = '/',
        string $query = '',
        string $fragment = '',
        int $port = null,
        string $user = null,
        string $password = null
    ) {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
        $this->port = $port;

        if ($user !== null) {
            $this->userInfo = $this->formatUserInfo($user, $password);
        }
    }

    /**
     * @inheritDoc
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * @inheritDoc
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * @inheritDoc
     */
    public function withScheme($scheme): UriInterface
    {
        $scheme = strtolower($scheme);

        if ($this->scheme === $scheme) {
            return $this;
        }

        $instance = clone $this;

        $instance->scheme = $scheme;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo($user, $password = null): UriInterface
    {
        $info = $this->formatUserInfo($user, $password);

        if ($this->userInfo === $info) {
            return $this;
        }

        $instance = clone $this;
        $instance->userInfo = $info;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withHost($host): UriInterface
    {
        $host = (string)$host;

        if ($this->host === $host) {
            return $this;
        }

        $instance = clone $this;
        $instance->host = $host;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withPort($port = null): UriInterface
    {
        if ($port !== null) {
            $port = (int)$port;
        }

        if ($this->port === $port) {
            return $this;
        }

        $instance = clone $this;
        $instance->port = $port;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withPath($path): UriInterface
    {
        $path = (string)$path;
        if ($this->path === $path) {
            return $this;
        }

        $instance = clone $this;
        $instance->path = $path;

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withQuery($query): UriInterface
    {
        $query = (string)$query;

        $instance = clone $this;
        $instance->query = str_replace('?', null, $query);

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withFragment($fragment): UriInterface
    {
        $fragment = (string)$fragment;

        $instance = clone $this;
        $instance->fragment = str_replace('#', null, $fragment);

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return implode(
            '',
            [
                $this->getScheme() . '://',
                $this->getAuthority(),
                $this->getPath(),
                empty($this->query) ? '' : "?{$this->getQuery()}",
                empty($this->fragment) ? '' : "#{$this->getFragment()}",
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getAuthority(): string
    {
        $authority = $this->host;

        if (!empty($this->userInfo)) {
            $authority = "{$this->userInfo}@{$authority}";
        }

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    private function formatUserInfo(string $user, string $password = null): string
    {
        $info = $user;

        if (!empty($info) && $password !== null) {
            $info .= ":$password";
        }

        return $info;
    }
}
