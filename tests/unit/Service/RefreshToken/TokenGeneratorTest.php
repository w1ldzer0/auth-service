<?php

declare(strict_types=1);

namespace unit\Service\RefreshToken;

use App\Shared\RefreshToken\Interfaces\CashManagerInterface;
use App\Shared\RefreshToken\RefreshTokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{
    private CashManagerInterface $refreshTokenManager;
    private RefreshTokenGenerator $generator;

    public function setUp(): void
    {
        $this->refreshTokenManager = $this->createMock(CashManagerInterface::class);
        $this->generator = new RefreshTokenGenerator($this->refreshTokenManager);
    }

    public function testGenerate(): void
    {
    }
}
