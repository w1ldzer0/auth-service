<?php

declare(strict_types=1);

namespace App\Auth\Oauth\Google\Command\Authorization;

use App\Auth\Shared\Exception\AuthorizationFailedException;
use App\Client\Google\Oauth\Exception\GoogleServiceException;
use App\Client\Google\Oauth\Exception\InvalidGrandException;
use App\Client\Google\Oauth\GoogleOauth2Client;
use App\Client\Google\Oauth\User;
use App\Shared\Exception\ExternalServerException;
use App\Shared\Traits\CommandDispatchTrait;
use App\Shared\ValueObject\PositiveInt;
use App\User\Service\CreateUserCommandHandler;
use Assert\Assertion;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class AuthorizationCommandHandler implements MessageHandlerInterface
{
    use CommandDispatchTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly GoogleOauth2Client $oauth2Client,
        private readonly CreateUserCommandFactory $createUserCommandFactory
    ) {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws AuthorizationFailedException
     * @throws ExternalServerException
     */
    public function __invoke(AuthorizationCommand $command): PositiveInt
    {
        $googleUser = $this->getGoogleUser($command);
        $createUserCommand = $this->createUserCommandFactory->make($googleUser);

        /** @see CreateUserCommandHandler::__invoke() */
        $userId = $this->handler($createUserCommand);

        Assertion::isInstanceOf($userId, PositiveInt::class);

        return $userId;
    }

    /**
     * @throws ExternalServerException
     * @throws AuthorizationFailedException
     */
    private function getGoogleUser(AuthorizationCommand $command): User
    {
        try {
            return $this->oauth2Client->getUser($command->getCode());
        } catch (InvalidGrandException $e) {
            throw new AuthorizationFailedException($e->getMessage(), $e->getCode(), $e);
        } catch (GoogleServiceException $e) {
            throw new ExternalServerException(
                'Getting user error: ' . $e->getMessage(), $e->getCode(), $e
            );
        }
    }
}
