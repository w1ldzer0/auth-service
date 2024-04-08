<?php

declare(strict_types=1);

namespace App\Auth\Password\Model;

use App\Shared\ValueObject\NotEmptyString;

class ChangePasswordCommand
{
    public function __construct(
        private readonly NotEmptyString $code,
        private readonly NotEmptyString $password
    ) {
    }

    public function getCode(): NotEmptyString
    {
        return $this->code;
    }

    public function getPassword(): NotEmptyString
    {
        return $this->password;
    }
}
