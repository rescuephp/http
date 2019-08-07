<?php

declare(strict_types=1);

namespace Rescue\Http\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Throwable;

class BadRequestException extends Exception implements HttpExceptionInterface
{
    public function __construct(
        string $message = 'Bad Request',
        int $code = StatusCodeInterface::STATUS_BAD_REQUEST,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
