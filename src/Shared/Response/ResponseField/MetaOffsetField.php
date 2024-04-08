<?php

declare(strict_types=1);

namespace App\Shared\Response\ResponseField;

class MetaOffsetField implements FieldInterface
{
    public function __construct(
        private readonly int $offset
    ) {
    }

    public function getValue(): int
    {
        return $this->offset;
    }

    public function getPath(): string
    {
        return FieldConstant::META_OFFSET_FIELD;
    }
}
