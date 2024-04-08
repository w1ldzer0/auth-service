<?php

declare(strict_types=1);

namespace App\Auth\Register\Service;

use App\Auth\Register\Event\UserRegisteredEvent;
use App\Auth\Register\Factory\CreateUserCommandByRegisterRequestFactory;
use App\Auth\Register\Factory\UpdateUserCommandByRegisterRequestFactory;
use App\Auth\Register\Listener\UserRegisteredListener;
use App\Auth\Register\Request\RegisterRequest;
use App\Shared\Exception\UniqueConstraintViolationException;
use App\Shared\Response\ErrorCode;
use App\Shared\Traits\CommandDispatchTrait;
use App\Shared\ValueObject\Email;
use App\Shared\ValueObject\PositiveInt;
use App\User\Model\UpdateUserCommand;
use App\User\Repository\UserRepositoryInterface;
use App\User\Service\CreateUserCommandHandler;
use Assert\Assertion;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterRequestHandler implements MessageHandlerInterface
{
    use CommandDispatchTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly RequestStack $requestStack
    ) {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws UniqueConstraintViolationException
     */
    public function __invoke(RegisterRequest $registerRequest): PositiveInt
    {
        $userId = $this->updateUser($registerRequest);

        if ($userId === null) {
            $userId = $this->createUser($registerRequest);
        }

        /* @see UserRegisteredListener::__invoke() */
        $this->eventDispatcher->dispatch(new UserRegisteredEvent($userId));

        return $userId;
    }

    private function updateUser(RegisterRequest $registerRequest): ?PositiveInt
    {
        $userRead = $this->userRepository->findByEmail(
            new Email($registerRequest->getEmail())
        );

        if ($userRead === null) {
            return null;
        }

        if ($userRead->isVerify()) {
            throw new UniqueConstraintViolationException(
                ErrorCode::USER_UNIQUE_KEY_EXIST,
                'User with given email is registered'
            );
        }

        $userId = new PositiveInt($userRead->getId());

        /* @see UpdateUserCommand::__invoke() */
        $this->handler(UpdateUserCommandByRegisterRequestFactory::make($userId, $registerRequest));

        return $userId;
    }

    private function createUser(RegisterRequest $registerRequest): PositiveInt
    {
        /* @see CreateUserCommandHandler::__invoke() */
        $userId = $this->handler(
            CreateUserCommandByRegisterRequestFactory::make($registerRequest)
        );

        Assertion::isInstanceOf($userId, PositiveInt::class);

        return $userId;
    }
}
