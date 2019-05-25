<?php

namespace Rescue\Http\Exception;

use Exception;
use Rescue\Http\ResponseInterface;
use Throwable;

class BadRequestException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Bad Request',
        int $code = ResponseInterface::STATUS_BAD_REQUEST,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
