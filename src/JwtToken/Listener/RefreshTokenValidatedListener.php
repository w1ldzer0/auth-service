<?php

declare(strict_types=1);

namespace App\JwtToken\Listener;

use App\JwtToken\Event\RefreshTokenValidatedEvent;
use App\JwtToken\Repository\RefreshTokenRepositoryInterface;
use App\Shared\ValueObject\NotEmptyString;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(RefreshTokenValidatedEvent::class)]
class RefreshTokenValidatedListener
{
    public function __construct(
        private readonly RefreshTokenRepositoryInterface $refreshTokenRepository
    ) {
    }

    public function __invoke(RefreshTokenValidatedEvent $event): void
    {
        $this->refreshTokenRepository->remove(
            new NotEmptyString((string) $event->getUserId())
        );
    }
}
