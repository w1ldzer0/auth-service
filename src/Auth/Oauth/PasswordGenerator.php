<?php

declare(strict_types=1);

namespace App\Auth\Oauth;

use App\Shared\ValueObject\NotEmptyString;
use Exception;
use RuntimeException;

class PasswordGenerator
{
    private const DEFAULT_PASSWORD_LENGTH = 64;

    public function __construct(
        private readonly int $passwordLength = self::DEFAULT_PASSWORD_LENGTH
    ) {
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function generate(): NotEmptyString
    {
        try {
            return new NotEmptyString(
                bin2hex(random_bytes($this->passwordLength))
            );
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
