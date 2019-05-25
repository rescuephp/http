<?php

namespace Rescue\Http\Exception;

use Exception;
use Rescue\Http\ResponseInterface;
use Throwable;

class MethodNotAllowedException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Method Not Allowed',
        int $code = ResponseInterface::STATUS_METHOD_NOT_ALLOWED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
