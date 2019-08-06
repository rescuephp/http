<?php

declare(strict_types=1);

namespace Rescue\Http\Exception;

use Exception;
use Rescue\Http\StatusCode;
use Throwable;

class BadRequestException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Bad Request',
        int $code = StatusCode::STATUS_BAD_REQUEST,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
