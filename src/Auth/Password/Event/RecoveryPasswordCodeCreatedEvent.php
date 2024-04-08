<?php

declare(strict_types=1);

namespace App\Auth\Password\Event;

use App\Shared\ValueObject\NotEmptyString;

class RecoveryPasswordCodeCreatedEvent
{
    public function __construct(
        private readonly int $userId,
        private readonly NotEmptyString $recoveryCode
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRecoveryCode(): NotEmptyString
    {
        return $this->recoveryCode;
    }
}
