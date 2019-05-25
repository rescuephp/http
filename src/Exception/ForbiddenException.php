<?php

namespace Rescue\Http\Exception;

use Exception;
use Rescue\Http\ResponseInterface;
use Throwable;

class ForbiddenException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Forbidden',
        int $code = ResponseInterface::STATUS_FORBIDDEN,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
