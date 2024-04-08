<?php

declare(strict_types=1);

namespace App\JwtToken;

use App\Shared\ValueObject\NotEmptyString;
use Exception;
use RuntimeException;

class RefreshTokenGenerator
{
    public const DEFAULT_REFRESH_TOKEN_LENGTH = 64;

    public function __construct(
        private readonly int $refreshTokenLength = self::DEFAULT_REFRESH_TOKEN_LENGTH
    ) {
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function generate(): NotEmptyString
    {
        try {
            return new NotEmptyString(
                bin2hex(random_bytes($this->refreshTokenLength))
            );
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
