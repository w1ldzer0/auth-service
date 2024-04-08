<?php

declare(strict_types=1);

namespace App\JwtToken;

use App\Shared\Exception\NotValidateCodeException;
use App\Shared\Response\ErrorCode;
use App\Shared\ValueObject\NotEmptyString;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class JwtTokenService
{
    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly PayloadFactory $payloadFactory,
        private readonly RefreshTokenService $refreshTokenService
    ) {
    }

    /**
     * @throws NotValidateCodeException
     */
    public function refresh(NotEmptyString $jwtToken): NotEmptyString
    {
        try {
            $payload = $this->payloadFactory->make($jwtToken);
        } catch (JWTDecodeFailureException) {
            throw new NotValidateCodeException(ErrorCode::NOT_VALIDATE_REFRESH_TOKEN);
        }

        $this->refreshTokenService->validate(
            $payload->getUser()->getId(),
            $payload->getRefreshToken()
        );

        return new NotEmptyString($this->jwtManager->create($payload->getUser()));
    }
}
