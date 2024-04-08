<?php

declare(strict_types=1);

namespace App\Shared\Response\ResponseField;

use App\Shared\Response\ErrorCode;

class ErrorCodeField implements FieldInterface
{
    public function __construct(
        private readonly ErrorCode $errorCode
    ) {
    }

    public function getValue(): string
    {
        return $this->errorCode->value;
    }

    public function getPath(): string
    {
        return FieldConstant::ERROR_CODE_FIELD;
    }
}
