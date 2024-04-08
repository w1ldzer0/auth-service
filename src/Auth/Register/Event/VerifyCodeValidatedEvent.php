<?php

declare(strict_types=1);

namespace App\Auth\Register\Event;

use App\Shared\ValueObject\PositiveInt;

class VerifyCodeValidatedEvent
{
    public function __construct(
        private readonly PositiveInt $userId,
    ) {
    }

    public function getUserId(): PositiveInt
    {
        return $this->userId;
    }
}
