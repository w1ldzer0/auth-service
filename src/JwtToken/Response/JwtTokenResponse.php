<?php

declare(strict_types=1);

namespace App\JwtToken\Response;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    required: ['jwt_token'],
    properties: [
        new Property('jwt_token', type: 'string'),
    ]
)]
class JwtTokenResponse
{
    public function __construct(
        public string $jwtToken,
    ) {
    }
}
