<?php

declare(strict_types=1);

namespace App\Auth\Oauth\Google\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class GoogleOauthRequest
{
    public function __construct(
        #[NotBlank]
        #[Required]
        private readonly string $code
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
