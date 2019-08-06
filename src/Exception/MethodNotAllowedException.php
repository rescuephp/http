<?php

declare(strict_types=1);

namespace Rescue\Http\Exception;

use Exception;
use Rescue\Http\StatusCode;
use Throwable;

class MethodNotAllowedException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Method Not Allowed',
        int $code = StatusCode::STATUS_METHOD_NOT_ALLOWED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
