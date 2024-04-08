<?php

declare(strict_types=1);

namespace App\Auth\Register\Service;

use App\Shared\ValueObject\NotEmptyString;
use Exception;
use RuntimeException;

class VerifyCodeGenerator
{
    private const DEFAULT_CODE_LENGTH = 6;

    public function __construct(
        private readonly int $codeLength = self::DEFAULT_CODE_LENGTH
    ) {
    }

    public function generate(): NotEmptyString
    {
        try {
            return new NotEmptyString($this->generateCode());
        } catch (Exception $exception) {
            throw new RuntimeException(
                'Failed to generate verification code: ' . $exception->getMessage(),
                (int) $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @throws Exception
     */
    private function generateCode(): string
    {
        $code = '';

        for ($i = 0; $i < $this->codeLength; ++$i) {
            $code .= random_int(0, 9);
        }

        return $code;
    }
}
