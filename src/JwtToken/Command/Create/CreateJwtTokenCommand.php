<?php

declare(strict_types=1);

namespace App\JwtToken\Command\Create;

use App\Shared\ValueObject\PositiveInt;

class CreateJwtTokenCommand
{
    public function __construct(
        private readonly PositiveInt $userId
    ) {
    }

    public function getUserId(): PositiveInt
    {
        return $this->userId;
    }
}
