<?php

declare(strict_types=1);

namespace App\Auth\Oauth\Google\Command\Authorization;

use App\Shared\ValueObject\NotEmptyString;

class AuthorizationCommand
{
    public function __construct(
        private readonly NotEmptyString $code
    ) {
    }

    public function getCode(): NotEmptyString
    {
        return $this->code;
    }
}
