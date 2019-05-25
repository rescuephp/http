<?php

namespace Rescue\Http\Factory;

use Rescue\Http\ResponseInterface;

interface ResponseFactoryInterface
{
    /**
     * Create a new response.
     *
     * @param int $code HTTP status code
     *
     * @return ResponseInterface
     */
    public function createResponse(int $code = ResponseInterface::STATUS_OK): ResponseInterface;
}
