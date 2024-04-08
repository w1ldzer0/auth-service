<?php

declare(strict_types=1);

namespace unit\Service\RefreshToken\Dto;

use App\Dto\User;
use App\Shared\RefreshToken\Dto\VerifyRefreshTokenDto;
use PHPUnit\Framework\TestCase;

class VerifyRefreshTokenDtoTest extends TestCase
{
    public function testConstruct()
    {
        $user = $this->createMock(User::class);

        $verifyRefreshToken = new VerifyRefreshTokenDto(
            'token',
            $user
        );

        self::assertEquals($user, $verifyRefreshToken->user);
        self::assertEquals('token', $verifyRefreshToken->token);
    }
}
