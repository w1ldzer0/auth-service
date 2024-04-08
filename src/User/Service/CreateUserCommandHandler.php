<?php

declare(strict_types=1);

namespace App\User\Service;

use App\Shared\Traits\CommandDispatchTrait;
use App\Shared\ValueObject\PositiveInt;
use App\User\Model\CreateUser;
use App\User\Model\CreateUserCommand;
use App\User\Model\UserPassword;
use App\User\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateUserCommandHandler implements MessageHandlerInterface
{
    use CommandDispatchTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasher $passwordHasher,
        private readonly RequestStack $requestStack,
    ) {
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreateUserCommand $createUserCommand): PositiveInt
    {
        $hashPassword = $this->passwordHasher->hashPassword(
            new UserPassword($createUserCommand->getPassword())
        );

        $userRead = $this->userRepository->create(
            new CreateUser(
                $createUserCommand->getEmail(),
                $hashPassword,
                $createUserCommand->getCountry(),
                $createUserCommand->getAdditionalContacts(),
            )
        );

        return new PositiveInt($userRead->getId());
    }
}
