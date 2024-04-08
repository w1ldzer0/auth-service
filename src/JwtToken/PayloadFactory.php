<?php

declare(strict_types=1);

namespace App\JwtToken;

use App\Shared\ValueObject\NotEmptyString;
use App\User\Model\ReadUser;
use App\User\Repository\UserRepositoryInterface;
use Assert\Assertion;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class PayloadFactory
{
    public function __construct(
        private readonly JWTEncoderInterface $JWTEncoder,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws JWTDecodeFailureException
     */
    public function make(NotEmptyString $jwtToken): Payload
    {
        $payload = $this->JWTEncoder->decode($jwtToken->getValue());

        return new Payload(
            $this->getUser($payload),
            $this->getRefreshToken($payload),
            $this->getIsLimitedToken($payload)
        );
    }

    private function getUser(array $payload): ReadUser
    {
        Assertion::keyExists($payload, Payload::USER_ID);
        $userId = $payload[Payload::USER_ID];
        Assertion::numeric($userId);

        return $this->userRepository->getById((int) $userId);
    }

    private function getRefreshToken(array $payload): NotEmptyString
    {
        Assertion::keyExists($payload, Payload::REFRESH_TOKEN);
        $refreshToken = $payload[Payload::REFRESH_TOKEN];
        Assertion::string($refreshToken);

        return new NotEmptyString($refreshToken);
    }

    private function getIsLimitedToken(array $payload): bool
    {
        if (array_key_exists(Payload::IS_LIMITED_TOKEN, $payload)) {
            return (bool) $payload[Payload::IS_LIMITED_TOKEN];
        }

        return false;
    }
}
