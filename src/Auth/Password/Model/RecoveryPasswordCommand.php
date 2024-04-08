<?php

declare(strict_types=1);

namespace App\Auth\Password\Model;

use App\Shared\ValueObject\Email;

class RecoveryPasswordCommand
{
    public function __construct(
        private readonly Email $getEmail
    ) {
    }

    public function getGetEmail(): Email
    {
        return $this->getEmail;
    }
}
