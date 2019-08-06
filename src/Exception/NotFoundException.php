<?php

declare(strict_types=1);

namespace Rescue\Http\Exception;

use Exception;
use Rescue\Http\StatusCode;
use Throwable;

class NotFoundException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Page not found',
        int $code = StatusCode::STATUS_NOT_FOUND,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
