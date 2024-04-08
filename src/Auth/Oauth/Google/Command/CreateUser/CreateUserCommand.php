<?php

declare(strict_types=1);

namespace App\Auth\Oauth\Google\Command\CreateUser;

use App\Shared\ValueObject\Email;
use App\Shared\ValueObject\NotEmptyString;

class CreateUserCommand
{
    public function __construct(
        private readonly NotEmptyString $externalId,
        private readonly Email $email
    ) {
    }

    public function getExternalId(): NotEmptyString
    {
        return $this->externalId;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}
