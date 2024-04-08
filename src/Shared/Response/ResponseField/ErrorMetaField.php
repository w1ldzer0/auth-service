<?php

declare(strict_types=1);

namespace App\Shared\Response\ResponseField;

class ErrorMetaField implements FieldInterface
{
    public function __construct(
        private readonly array $errorMeta
    ) {
    }

    public function getValue(): array
    {
        return $this->errorMeta;
    }

    public function getPath(): string
    {
        return FieldConstant::ERROR_META_FIELD;
    }
}
