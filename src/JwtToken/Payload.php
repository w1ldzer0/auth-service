<?php

declare(strict_types=1);

namespace App\JwtToken;

use App\Shared\ValueObject\NotEmptyString;
use App\User\Model\ReadUser;

class Payload
{
    public const USER_ID = 'user_id';
    public const REFRESH_TOKEN = 'refresh_token';
    public const IS_LIMITED_TOKEN = 'is_limited_token';

    public function __construct(
        private readonly ReadUser $user,
        private readonly NotEmptyString $refreshToken,
        private readonly bool $isLimitedToken = false
    ) {
    }

    public function getUser(): ReadUser
    {
        return $this->user;
    }

    public function getRefreshToken(): NotEmptyString
    {
        return $this->refreshToken;
    }

    public function isLimitedToken(): bool
    {
        return $this->isLimitedToken;
    }
}
