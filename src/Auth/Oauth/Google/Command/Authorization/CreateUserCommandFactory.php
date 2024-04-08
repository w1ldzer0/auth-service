<?php

declare(strict_types=1);

namespace App\Auth\Oauth\Google\Command\Authorization;

use App\Auth\Oauth\Google\Command\CreateUser\CreateUserCommand;
use App\Client\Google\Oauth\User;
use Assert\Assertion;

class CreateUserCommandFactory
{
    public function make(User $googleUser): CreateUserCommand
    {
        $email = $googleUser->getEmail();
        Assertion::notNull($email);

        return new CreateUserCommand($googleUser->getGoogleId(), $email);
    }
}
