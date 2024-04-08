<?php

declare(strict_types=1);

namespace App\Auth\Register\Request;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class RegisterEmailRequest
{
    public function __construct(
        #[Required]
        #[NotBlank]
        private readonly int $userId,
        #[Required]
        #[Email]
        private readonly string $email
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
