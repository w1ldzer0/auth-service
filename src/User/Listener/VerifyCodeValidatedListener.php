<?php

declare(strict_types=1);

namespace App\User\Listener;

use App\Auth\Register\Event\VerifyCodeValidatedEvent;
use App\User\Model\UpdateUser;
use App\User\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(VerifyCodeValidatedEvent::class)]
class VerifyCodeValidatedListener
{
    public function __construct(
        private readonly UserRepositoryInterface $verifyCodeRepository
    ) {
    }

    public function __invoke(VerifyCodeValidatedEvent $event): void
    {
        $userId = $event->getUserId();

        $updateUser = (new UpdateUser($userId))
            ->setLastLogin(new DateTimeImmutable())
            ->setIsVerify(true);

        $this->verifyCodeRepository->update($updateUser);
    }
}
