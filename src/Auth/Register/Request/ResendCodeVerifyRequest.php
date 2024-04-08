<?php

declare(strict_types=1);

namespace App\Auth\Register\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class ResendCodeVerifyRequest
{
    public function __construct(
        #[Required]
        #[NotBlank]
        private readonly int $userId,
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
