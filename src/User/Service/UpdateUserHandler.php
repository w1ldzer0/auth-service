<?php

declare(strict_types=1);

namespace App\User\Service;

use App\Shared\Exception\UniqueConstraintViolationException;
use App\User\Model\UpdateUser;
use App\User\Model\UpdateUserCommand;
use App\User\Model\UserPassword;
use App\User\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateUserHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasher $passwordHasher,
    ) {
    }

    /**
     * @throws UniqueConstraintViolationException
     */
    public function __invoke(UpdateUserCommand $updateUserCommand): void
    {
        $password = $updateUserCommand->getPassword();
        $hashPassword = $password ? $this->passwordHasher->hashPassword(
            new UserPassword($password)
        ) : null;

        $updateUser = (new UpdateUser($updateUserCommand->getId()))
            ->setFirstName($updateUserCommand->getFirstName())
            ->setLastName($updateUserCommand->getLastName())
            ->setPassword($hashPassword)
            ->setCountry($updateUserCommand->getCountry())
            ->setSite($updateUserCommand->getSite())
            ->setPhone($updateUserCommand->getPhone())
            ->setIndustry($updateUserCommand->getIndustry())
            ->setBudget($updateUserCommand->getBudget())
            ->setAdditionalContacts($updateUserCommand->getAdditionalContacts());

        $this->userRepository->update($updateUser);
    }
}
