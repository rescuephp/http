<?php

namespace Rescue\Http\Exception;

use Exception;
use Rescue\Http\ResponseInterface;
use Throwable;

class UnauthorizedException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Unauthorized',
        int $code = ResponseInterface::STATUS_UNAUTHORIZED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
