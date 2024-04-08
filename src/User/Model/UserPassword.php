<?php

declare(strict_types=1);

namespace App\User\Model;

use App\Shared\ValueObject\NotEmptyString;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserPassword implements PasswordAuthenticatedUserInterface
{
    public function __construct(
        private readonly NotEmptyString $password
    ) {
    }

    public function getPassword(): string
    {
        return $this->password->getValue();
    }
}
