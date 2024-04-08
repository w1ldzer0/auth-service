<?php

declare(strict_types=1);

namespace App\Auth\Password\Request;

use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class ChangePasswordRequest
{
    public function __construct(
        #[Required]
        #[NotBlank]
        private readonly string $password,
        #[Required]
        #[NotBlank]
        #[EqualTo(propertyPath: 'password')]
        private readonly string $confirmPassword
    ) {
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }
}
