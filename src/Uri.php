<?php

namespace Rescue\Http;

class Uri implements UriInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $userInfo = '';

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $query;

    /**
     * @var string
     */
    private $fragment;

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
    public function withScheme(string $scheme): UriInterface
    {
        if ($this->scheme === $scheme) {
            return $this;
        }

        $instance = clone $this;

        $instance->scheme = strtolower($scheme);

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo(string $user, string $password = null): UriInterface
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
    public function withHost(string $host): UriInterface
    {
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
    public function withPort(int $port = null): UriInterface
    {
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
    public function withPath(string $path): UriInterface
    {
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
    public function withQuery(string $query): UriInterface
    {
        $instance = clone $this;
        $instance->query = str_replace('?', null, $query);

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withFragment(string $fragment): UriInterface
    {
        $instance = clone $this;
        $instance->fragment = str_replace('#', null, $fragment);

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return implode('', [
            $this->getScheme() . '://',
            $this->getAuthority(),
            $this->getPath(),
            empty($this->query) ? '' : "?{$this->getQuery()}",
            empty($this->fragment) ? '' : "#{$this->getFragment()}",
        ]);
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
