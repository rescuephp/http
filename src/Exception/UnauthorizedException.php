<?php

declare(strict_types=1);

namespace Rescue\Http\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Throwable;

class UnauthorizedException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Unauthorized',
        int $code = StatusCodeInterface::STATUS_UNAUTHORIZED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
