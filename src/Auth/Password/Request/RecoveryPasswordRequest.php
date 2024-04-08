<?php

declare(strict_types=1);

namespace App\Auth\Password\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class RecoveryPasswordRequest
{
    public function __construct(
        #[Required]
        #[NotBlank]
        private readonly string $email
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
