<?php

declare(strict_types=1);

namespace App\Auth\Register\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class RegisterVerifyRequest
{
    public function __construct(
        #[Required]
        #[NotBlank]
        private readonly int $userId,
        #[Required]
        #[NotBlank]
        private readonly string $code,
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
