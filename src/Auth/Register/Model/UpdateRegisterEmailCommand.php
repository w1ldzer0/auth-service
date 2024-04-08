<?php

declare(strict_types=1);

namespace App\Auth\Register\Model;

use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;

class UpdateRegisterEmailCommand
{
    public function __construct(
        private readonly PositiveInt $userId,
        private readonly NotEmptyString $email
    ) {
    }

    public function getUserId(): PositiveInt
    {
        return $this->userId;
    }

    public function getEmail(): NotEmptyString
    {
        return $this->email;
    }
}
