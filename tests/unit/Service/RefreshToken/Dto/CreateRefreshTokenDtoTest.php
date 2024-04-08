<?php

declare(strict_types=1);

namespace unit\Service\RefreshToken\Dto;

use App\Dto\User;
use App\Shared\RefreshToken\Dto\CreateRefreshTokenDto;
use PHPUnit\Framework\TestCase;

class CreateRefreshTokenDtoTest extends TestCase
{
    public function testConstruct(): void
    {
        $user = $this->createMock(User::class);

        $createRefreshDto = new CreateRefreshTokenDto(
            $user,
            1234
        );

        self::assertEquals($user, $createRefreshDto->user);
        self::assertEquals(1234, $createRefreshDto->ttlSeconds);
    }
}
