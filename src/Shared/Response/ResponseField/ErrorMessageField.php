<?php

declare(strict_types=1);

namespace App\Shared\Response\ResponseField;

class ErrorMessageField implements FieldInterface
{
    public function __construct(
        private readonly string $errorMessage
    ) {
    }

    public function getValue(): string
    {
        return $this->errorMessage;
    }

    public function getPath(): string
    {
        return FieldConstant::ERROR_MESSAGE_FIELD;
    }
}
