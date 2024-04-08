<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use InvalidArgumentException;
use JsonSerializable;

class PositiveInt implements JsonSerializable
{
    public function __construct(private readonly int $value)
    {
        if ($this->value < 0) {
            throw new InvalidArgumentException('Value must be greater than zero');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
