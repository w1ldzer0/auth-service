<?php

declare(strict_types=1);

namespace App\Auth\Password\Service;

use App\Shared\ValueObject\NotEmptyString;
use Exception;
use RuntimeException;

class RecoveryPasswordCodeGenerator
{
    private const DEFAULT_RECOVERY_PASSWORD_CODE_LENGTH = 32;

    public function __construct(
        private readonly int $recoveryPasswordCodeLength = self::DEFAULT_RECOVERY_PASSWORD_CODE_LENGTH
    ) {
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function generate(): NotEmptyString
    {
        try {
            return new NotEmptyString(
                bin2hex(random_bytes($this->recoveryPasswordCodeLength))
            );
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
