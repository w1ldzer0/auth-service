<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use InvalidArgumentException;
use JsonSerializable;

class NotEmptyString implements JsonSerializable
{
    public function __construct(private readonly string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('Value cannot be empty');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equal(NotEmptyString $value): bool
    {
        return $this->getValue() === $value->getValue();
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function jsonSerialize(): string
    {
        return $this->getValue();
    }
}
