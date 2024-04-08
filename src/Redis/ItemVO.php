<?php

declare(strict_types=1);

namespace App\Redis;

use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;

class ItemVO
{
    public function __construct(
        private readonly NotEmptyString $key,
        private readonly NotEmptyString $value,
        private readonly ?PositiveInt $ttl = null
    ) {
    }

    public function getKey(): NotEmptyString
    {
        return $this->key;
    }

    public function getValue(): NotEmptyString
    {
        return $this->value;
    }

    public function getTtl(): ?PositiveInt
    {
        return $this->ttl;
    }
}
