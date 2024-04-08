<?php

declare(strict_types=1);

namespace App\Auth\Register\Service;

use App\Auth\Register\Event\UpdateRegisterEmailEvent;
use App\Auth\Register\Listener\UpdateRegisterEmailListener;
use App\Auth\Register\Model\UpdateRegisterEmailCommand;
use App\User\Model\UpdateUser;
use App\User\Repository\UserRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateRegisterEmailHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    public function __invoke(UpdateRegisterEmailCommand $event): void
    {
        $this->userRepository->update(
            (new UpdateUser($event->getUserId()))
                ->setEmail($event->getEmail())
        );

        /* @see  UpdateRegisterEmailListener::__invoke() */
        $this->dispatcher->dispatch(new UpdateRegisterEmailEvent($event->getUserId()));
    }
}
