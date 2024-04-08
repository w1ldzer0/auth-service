<?php

declare(strict_types=1);

namespace App\Auth\Login\Listener;

use App\Auth\Password\Repository\RecoveryPasswordCodeRepositoryInterface;
use App\Shared\ValueObject\NotEmptyString;
use App\User\Event\ClearUserAuthDataEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(ClearUserAuthDataEvent::class)]
class ClearUserAuthDataListener
{
    public function __construct(
        private readonly RecoveryPasswordCodeRepositoryInterface $repository
    ) {
    }

    public function __invoke(ClearUserAuthDataEvent $event): void
    {
        $this->repository->remove(
            new NotEmptyString((string) $event->getUserId())
        );
    }
}
