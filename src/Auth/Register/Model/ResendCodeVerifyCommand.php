<?php

declare(strict_types=1);

namespace App\Auth\Register\Model;

use App\Shared\ValueObject\PositiveInt;

class ResendCodeVerifyCommand
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
