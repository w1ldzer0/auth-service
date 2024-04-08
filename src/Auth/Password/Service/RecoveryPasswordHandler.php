<?php

declare(strict_types=1);

namespace App\Auth\Password\Service;

use App\Auth\Password\Event\RecoveryPasswordCodeCreatedEvent;
use App\Auth\Password\Model\RecoveryPasswordCommand;
use App\Auth\Password\Repository\RecoveryPasswordCodeRepositoryInterface;
use App\Notification\Listener\RecoveryPasswordCodeCreatedListener;
use App\Shared\Exception\NotFoundException;
use App\Shared\ValueObject\Email;
use App\Shared\ValueObject\NotEmptyString;
use App\User\Model\ReadUser;
use App\User\Repository\UserRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

#[AsEventListener(RecoveryPasswordCommand::class)]
class RecoveryPasswordHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
        private readonly UserRepositoryInterface $userRepository,
        private readonly RecoveryPasswordCodeService $recoveryPasswordCodeService,
        private readonly RecoveryPasswordCodeRepositoryInterface $repository
    ) {
    }

    public function __invoke(RecoveryPasswordCommand $event): void
    {
        $user = $this->getUser($event->getGetEmail());
        $userId = $user->getId();
        $recoveryPasswordCode = $this->recoveryPasswordCodeService->create($user->getId());

        $this->repository->save(new NotEmptyString((string) $userId), $recoveryPasswordCode);

        /* @see RecoveryPasswordCodeCreatedListener::__invoke() */
        $this->dispatcher->dispatch(
            new RecoveryPasswordCodeCreatedEvent($userId, $recoveryPasswordCode)
        );
    }

    private function getUser(Email $email): ReadUser
    {
        try {
            $user = $this->userRepository->getByEmail($email);
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException('User with given email does not exist');
        }

        if ($user->isVerify()) {
            return $user;
        }

        throw new NotFoundHttpException('User with given email does not exist');
    }
}
