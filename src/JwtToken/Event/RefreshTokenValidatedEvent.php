<?php

declare(strict_types=1);

namespace App\JwtToken\Event;

use App\Shared\ValueObject\NotEmptyString;

class RefreshTokenValidatedEvent
{
    public function __construct(
        private readonly int $userId,
        private readonly NotEmptyString $refreshToken
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRefreshToken(): NotEmptyString
    {
        return $this->refreshToken;
    }
}
