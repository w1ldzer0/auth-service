<?php

declare(strict_types=1);

namespace App\JwtToken;

use App\JwtToken\Event\RefreshTokenValidatedEvent;
use App\JwtToken\Listener\RefreshTokenValidatedListener;
use App\JwtToken\Model\JwtUser;
use App\JwtToken\Repository\RefreshTokenRepositoryInterface;
use App\Shared\Exception\NotFoundException;
use App\Shared\Exception\NotValidateCodeException;
use App\Shared\Response\ErrorCode;
use App\Shared\ValueObject\NotEmptyString;
use Psr\EventDispatcher\EventDispatcherInterface;

class RefreshTokenService
{
    public function __construct(
        private readonly RefreshTokenRepositoryInterface $refreshTokenRepository,
        private readonly RefreshTokenGenerator $generator,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    public function create(JwtUser $user): NotEmptyString
    {
        $refreshToken = $this->generator->generate();
        $userId = $user->getUserIdentifier();

        $this->refreshTokenRepository->save(new NotEmptyString($userId), $refreshToken);

        return $refreshToken;
    }

    /**
     * @throws NotValidateCodeException
     */
    public function validate(int $userId, NotEmptyString $refreshToken): void
    {
        try {
            $currentRefreshToken = $this->refreshTokenRepository->get(
                new NotEmptyString((string) $userId)
            );
        } catch (NotFoundException) {
            throw $this->exceptionNotValidToken();
        }

        if (!$currentRefreshToken->equal($refreshToken)) {
            throw $this->exceptionNotValidToken();
        }

        /* @see RefreshTokenValidatedListener::__invoke() */
        $this->dispatcher->dispatch(
            new RefreshTokenValidatedEvent($userId, $refreshToken)
        );
    }

    private function exceptionNotValidToken(): NotValidateCodeException
    {
        return new NotValidateCodeException(ErrorCode::NOT_VALIDATE_REFRESH_TOKEN);
    }
}
