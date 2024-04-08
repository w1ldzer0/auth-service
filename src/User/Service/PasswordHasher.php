<?php

declare(strict_types=1);

namespace App\User\Service;

use App\Shared\ValueObject\NotEmptyString;
use App\User\Model\UserPassword;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasher
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function hashPassword(UserPassword $userPassword): NotEmptyString
    {
        $password = $this->passwordHasher->hashPassword(
            $userPassword,
            $userPassword->getPassword()
        );

        return new NotEmptyString($password);
    }
}
