<?php

declare(strict_types=1);

namespace App\Shared\Exception;

use App\Shared\Response\ErrorCode;
use Exception;
use Throwable;

class UniqueConstraintViolationException extends Exception
{
    public function __construct(
        private readonly ErrorCode $errorCode,
        string $message = '', int $code = 0, ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getErrorCode(): ErrorCode
    {
        return $this->errorCode;
    }
}
