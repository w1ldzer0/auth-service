<?php

declare(strict_types=1);

namespace App\Auth\Password\Listener;

use App\Auth\Password\Event\RecoveryPasswordCodeValidatedEvent;
use App\Auth\Password\Repository\RecoveryPasswordCodeRepositoryInterface;
use App\Shared\ValueObject\NotEmptyString;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(RecoveryPasswordCodeValidatedEvent::class)]
class RecoveryPasswordCodeValidatedListener
{
    public function __construct(
        private readonly RecoveryPasswordCodeRepositoryInterface $recoveryPasswordCodeRepository
    ) {
    }

    public function __invoke(RecoveryPasswordCodeValidatedEvent $event): void
    {
        $this->recoveryPasswordCodeRepository->remove(
            new NotEmptyString((string) $event->getId())
        );
    }
}
