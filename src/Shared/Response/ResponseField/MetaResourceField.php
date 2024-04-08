<?php

declare(strict_types=1);

namespace App\Shared\Response\ResponseField;

class MetaResourceField implements FieldInterface
{
    public function __construct(
        private readonly string $metaResource
    ) {
    }

    public function getValue(): string
    {
        return $this->metaResource;
    }

    public function getPath(): string
    {
        return FieldConstant::META_RESOURCE_FIELD;
    }
}
