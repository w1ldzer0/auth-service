<?php

declare(strict_types=1);

namespace App\JwtToken\Response;

class PublicKeyResponse
{
    public function __construct(
        public string $publicKey
    ) {
    }
}
