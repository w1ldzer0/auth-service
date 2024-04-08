<?php

declare(strict_types=1);

namespace App\Auth\Register\Event;

use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;

class VerifyCodeCreatedEvent
{
    public function __construct(
        private readonly PositiveInt $userId,
        private readonly NotEmptyString $verifyCode
    ) {
    }

    public function getUserId(): PositiveInt
    {
        return $this->userId;
    }

    public function getVerifyCode(): NotEmptyString
    {
        return $this->verifyCode;
    }
}
