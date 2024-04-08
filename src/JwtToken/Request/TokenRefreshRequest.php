<?php

declare(strict_types=1);

namespace App\JwtToken\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class TokenRefreshRequest
{
    public function __construct(
        #[Required]
        #[NotBlank]
        private readonly string $jwtToken
    ) {
    }

    public function getJwtToken(): string
    {
        return $this->jwtToken;
    }
}
