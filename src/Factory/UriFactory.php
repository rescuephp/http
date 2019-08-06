<?php

declare(strict_types=1);

namespace Rescue\Http\Factory;

use InvalidArgumentException;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Rescue\Http\Uri;

class UriFactory implements UriFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createUri(string $uri = ''): UriInterface
    {
        $parts = parse_url($uri);

        if ($parts === false) {
            throw new InvalidArgumentException('Invalid URI');
        }

        [$scheme, $host, $path, $query, $fragment, $user, $pass, $port] = [
            $parts['scheme'] ?? '',
            $parts['host'] ?? '',
            $parts['path'] ?? '',
            $parts['query'] ?? '',
            $parts['fragment'] ?? '',
            $parts['user'] ?? '',
            $parts['pass'] ?? '',
            $parts['port'] ?? null,
        ];

        return new Uri($scheme, $host, $path, $query, $fragment, $port, $user, $pass);
    }
}
