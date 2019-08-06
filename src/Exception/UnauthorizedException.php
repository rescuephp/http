<?php

declare(strict_types=1);

namespace Rescue\Http\Exception;

use Exception;
use Rescue\Http\StatusCode;
use Throwable;

class UnauthorizedException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Unauthorized',
        int $code = StatusCode::STATUS_UNAUTHORIZED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
