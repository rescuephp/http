<?php

declare(strict_types=1);

namespace Rescue\Http\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Throwable;

class ForbiddenException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Forbidden',
        int $code = StatusCodeInterface::STATUS_FORBIDDEN,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
