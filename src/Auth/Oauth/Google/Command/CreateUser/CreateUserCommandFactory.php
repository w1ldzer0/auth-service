<?php

declare(strict_types=1);

namespace App\Auth\Oauth\Google\Command\CreateUser;

use App\Auth\Oauth\PasswordGenerator;
use App\User\Model\CreateUserCommand as MainCreateUserCommand;

class CreateUserCommandFactory
{
    public function __construct(
        private readonly PasswordGenerator $passwordGenerator,
    ) {
    }

    public function make(CreateUserCommand $command): MainCreateUserCommand
    {
        return new MainCreateUserCommand(
            $command->getEmail(),
            $this->passwordGenerator->generate()
        );
    }
}
