<?php

declare(strict_types=1);

namespace App\JwtToken\Listener;

use App\JwtToken\Repository\RefreshTokenRepositoryInterface;
use App\Shared\ValueObject\NotEmptyString;
use App\User\Event\ClearUserAuthDataEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(ClearUserAuthDataEvent::class)]
class ClearUserAuthDataListener
{
    public function __construct(
        private readonly RefreshTokenRepositoryInterface $repository
    ) {
    }

    public function __invoke(ClearUserAuthDataEvent $event): void
    {
        $this->repository->remove(
            new NotEmptyString((string) $event->getUserId())
        );
    }
}
