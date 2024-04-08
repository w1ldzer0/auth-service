<?php

declare(strict_types=1);

namespace App\Auth\Password\Service;

use App\Auth\Password\Repository\RecoveryPasswordCodeRepositoryInterface;
use App\Shared\Exception\NotFoundException;
use App\Shared\Exception\NotValidateCodeException;
use App\Shared\Response\ErrorCode;
use App\Shared\ValueObject\NotEmptyString;
use InvalidArgumentException;

class RecoveryPasswordCodeService
{
    private const SEPARATOR = ';';

    public function __construct(
        private readonly RecoveryPasswordCodeGenerator $codeGenerator,
        private readonly RecoveryPasswordCodeRepositoryInterface $recoveryPasswordCodeRepository,
    ) {
    }

    public function create(int $userId): NotEmptyString
    {
        $code = $this->generate($userId);

        $this->recoveryPasswordCodeRepository->save(
            new NotEmptyString((string) $userId),
            $code
        );

        return $code;
    }

    public function getUserId(NotEmptyString $code): int
    {
        return (int) $this->decode($code)[0];
    }

    private function decode(NotEmptyString $code): array
    {
        $codeDecode = base64_decode($code->getValue());

        if ($codeDecode === false) {
            throw new InvalidArgumentException('Not validate code');
        }

        return explode(self::SEPARATOR, $codeDecode);
    }

    /**
     * @throws NotValidateCodeException
     */
    public function validate(NotEmptyString $recoveryPasswordCode): void
    {
        try {
            $userId = $this->getUserId($recoveryPasswordCode);
        } catch (InvalidArgumentException) {
            throw $this->exceptionNotValidateRecoveryCode();
        }

        try {
            $currentCode = $this->recoveryPasswordCodeRepository->get(
                new NotEmptyString((string) $userId)
            );
        } catch (NotFoundException) {
            throw $this->exceptionNotValidateRecoveryCode();
        }

        if (!$currentCode->equal($recoveryPasswordCode)) {
            throw $this->exceptionNotValidateRecoveryCode();
        }
    }

    private function generate(int $userId): NotEmptyString
    {
        $code = base64_encode(
            implode(self::SEPARATOR, [
                $userId,
                $this->codeGenerator->generate()->getValue(),
            ])
        );

        return new NotEmptyString($code);
    }

    private function exceptionNotValidateRecoveryCode(): NotValidateCodeException
    {
        return new NotValidateCodeException(ErrorCode::NOT_VALIDATE_RECOVERY_PASSWORD_CODE);
    }
}
