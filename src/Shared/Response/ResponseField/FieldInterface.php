<?php

declare(strict_types=1);

namespace App\Shared\Response\ResponseField;

interface FieldInterface
{
    public function getValue(): mixed;

    public function getPath(): string;
}
