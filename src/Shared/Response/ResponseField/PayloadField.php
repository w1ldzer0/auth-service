<?php

declare(strict_types=1);

namespace App\Shared\Response\ResponseField;

class PayloadField implements FieldInterface
{
    public function __construct(
        private readonly mixed $payload
    ) {
    }

    public function getValue(): mixed
    {
        return $this->payload;
    }

    public function getPath(): string
    {
        return FieldConstant::PAYLOAD_FIELD;
    }
}
