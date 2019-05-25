<?php

namespace Rescue\Http\Factory;

use InvalidArgumentException;
use Rescue\Http\ServerRequest;
use Rescue\Http\ServerRequestInterface;
use Rescue\Http\UriInterface;

class ServerRequestFactory implements ServerRequestFactoryInterface
{
    private const DEFAULT_HTTP_PROTOCOL_VERSION = '1.1';

    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    public function __construct(
        UriFactoryInterface $uriFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->uriFactory = $uriFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @inheritDoc
     */
    public function createServerRequestFromArray(array $server): ServerRequestInterface
    {
        [$uri, $port, $method, $host, $user, $password, $protocol] = [
            $server['REQUEST_URI'] ?? null,
            $server['SERVER_PORT'] ?? null,
            $server['REQUEST_METHOD'] ?? null,
            $server['HTTP_HOST'] ?? null,
            $server['PHP_AUTH_USER'] ?? '',
            $server['PHP_AUTH_PW'] ?? '',
            $server['SERVER_PROTOCOL'] ?? 'HTTP/' . self::DEFAULT_HTTP_PROTOCOL_VERSION,
        ];

        if ($uri === null) {
            throw new InvalidArgumentException('Invalid SERVER_URI');
        }

        if ($method === null) {
            throw new InvalidArgumentException('Invalid REQUEST_METHOD');
        }

        $protocol = str_replace('HTTP/', null, $protocol);

        if (!empty($host)) {
            $host = parse_url($host, PHP_URL_HOST);
        }

        $uriInstance = $this
            ->uriFactory
            ->createUri($uri)
            ->withPort($port)
            ->withHost($host)
            ->withUserInfo($user, $password);

        $headers = $this->parseHeaders($server);

        $body = $this->streamFactory->createStream();

        return new ServerRequest($method, $uriInstance, $headers, $body, $protocol, $server);
    }

    /**
     * @inheritDoc
     */
    public function createServerRequest(string $method, $uri): ServerRequestInterface
    {
        if (!$uri instanceof UriInterface) {
            $uri = $this->uriFactory->createUri($uri);
        }

        $body = $this->streamFactory->createStream();

        return new ServerRequest($method, $uri, [], $body, self::DEFAULT_HTTP_PROTOCOL_VERSION);
    }

    /**
     * @param array $server
     * @return string[][]
     */
    private function parseHeaders(array $server): array
    {
        $headers = [];

        $serverHeaders = array_filter($server, static function (string $key) {
            return strpos($key, 'HTTP') === 0;
        }, ARRAY_FILTER_USE_KEY);

        foreach ($serverHeaders as $name => $value) {
            $name = str_replace(['HTTP_', ' ', '_'], ['', '', '-'], $name);
            $name = strtolower($name);

            $headers[$name][] = $value;
        }

        return $headers;
    }
}
