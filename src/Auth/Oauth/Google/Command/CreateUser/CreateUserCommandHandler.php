<?php

declare(strict_types=1);

namespace App\Auth\Oauth\Google\Command\CreateUser;

use App\Shared\Traits\CommandDispatchTrait;
use App\Shared\ValueObject\PositiveInt;
use App\User\Repository\UserRepositoryInterface;
use Assert\Assertion;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class CreateUserCommandHandler implements MessageHandlerInterface
{
    use CommandDispatchTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CreateUserCommandFactory $createUserCommandFactory,
    ) {
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreateUserCommand $command)
    {
        $user = $this->userRepository->findByEmail($command->getEmail());

        return $user === null ?
            $this->createUser($command) :
            new PositiveInt($user->getId());
    }

    private function createUser(CreateUserCommand $command): PositiveInt
    {
        $createUserCommand = $this->createUserCommandFactory->make($command);

        /** @see \App\User\Service\CreateUserCommandHandler::__invoke() */
        $userId = $this->handler($createUserCommand);

        Assertion::isInstanceOf($userId, PositiveInt::class);

        return $userId;
    }
}
