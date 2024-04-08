<?php

declare(strict_types=1);

namespace App\Auth\Login\Request;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Service\Attribute\Required;

class LoginRequest
{
    public function __construct(
        #[Required]
        #[NotBlank]
        #[Type('string')]
        #[Length(min: 4, max: 100)]
        public string $email,
        #[Required]
        #[NotBlank]
        #[Type('string')]
        public string $password,
    ) {
    }
}
