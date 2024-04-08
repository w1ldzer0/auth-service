<?php

declare(strict_types=1);

namespace App\Auth\Password\Service;

use App\Auth\Login\Listener\ClearUserAuthDataListener;
use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
use App\User\Event\ClearUserAuthDataEvent;
use App\User\Model\UpdateUser;
use App\User\Model\UserPassword;
use App\User\Repository\UserRepositoryInterface;
use App\User\Service\PasswordHasher;
use Assert\Assertion;
use Psr\EventDispatcher\EventDispatcherInterface;

class UpdatePasswordService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasher $passwordHasher,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    public function update(int $userId, NotEmptyString $password): void
    {
        $updateUser = (new UpdateUser(new PositiveInt($userId)));
        $updateUser->setPassword($password);
        $this->hashedPassword($updateUser);
        $this->userRepository->update($updateUser);

        /* @see ClearUserAuthDataListener::__invoke */
        $this->dispatcher->dispatch(new ClearUserAuthDataEvent($userId));
    }

    private function hashedPassword(UpdateUser $user): void
    {
        $password = $user->getPassword();
        Assertion::notEmpty($password);

        $hashPassword = $this->passwordHasher->hashPassword(
            new UserPassword($password)
        );

        $user->setPassword($hashPassword);
    }
}
