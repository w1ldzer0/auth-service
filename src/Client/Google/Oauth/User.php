<?php

declare(strict_types=1);

namespace App\Client\Google\Oauth;

use App\Shared\ValueObject\Email;
use App\Shared\ValueObject\NotEmptyString;

class User
{
    public function __construct(
        private readonly NotEmptyString $googleId,
        private readonly ?Email $email = null
    ) {
    }

    public function getGoogleId(): NotEmptyString
    {
        return $this->googleId;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }
}
