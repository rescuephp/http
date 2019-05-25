<?php

namespace Rescue\Http;

use Rescue\Helper\Formatter\Exception\FormatterException;
use Rescue\Helper\Formatter\FormatterInterface;

interface RequestHandlerInterface
{
    public function getResponse(): ResponseInterface;

    public function withFormatter(FormatterInterface $formatter): RequestHandlerInterface;

    public function withResponse(ResponseInterface $response): RequestHandlerInterface;

    /**
     * @param mixed $message
     * @return ResponseInterface
     * @throws FormatterException
     */
    public function send($message): ResponseInterface;

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface;
}
