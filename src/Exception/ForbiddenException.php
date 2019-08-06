<?php

declare(strict_types=1);

namespace Rescue\Http\Exception;

use Exception;
use Rescue\Http\StatusCode;
use Throwable;

class ForbiddenException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Forbidden',
        int $code = StatusCode::STATUS_FORBIDDEN,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
