<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use Assert\Assertion;

class Email
{
    public function __construct(private readonly string $value)
    {
        Assertion::email($this->value);
    }

    public function getValue(): string
    {
        return $this->value;
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
