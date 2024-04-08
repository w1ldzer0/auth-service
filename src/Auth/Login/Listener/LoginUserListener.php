<?php

declare(strict_types=1);

namespace App\Auth\Login\Listener;

use App\Auth\Login\Event\LoginUserEvent;
use App\User\Model\UpdateUser;
use App\User\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(LoginUserEvent::class)]
class LoginUserListener
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(LoginUserEvent $event): void
    {
        $updateUser = (new UpdateUser($event->getUserId()))
            ->setLastLogin(new DateTimeImmutable());

        $this->userRepository->update($updateUser);
    }
}
