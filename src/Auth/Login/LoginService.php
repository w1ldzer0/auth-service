<?php

declare(strict_types=1);

namespace App\Auth\Login;

use App\Auth\Login\Event\LoginUserEvent;
use App\Auth\Login\Listener\LoginUserListener;
use App\Auth\Login\Request\LoginRequest;
use App\Shared\Exception\AuthenticationException;
use App\Shared\Response\ErrorCode;
use App\Shared\ValueObject\Email;
use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
use App\User\Model\ReadUser;
use App\User\Model\UserPassword;
use App\User\Repository\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    public function login(LoginRequest $loginRequest): ReadUser
    {
        $user = $this->getUser($loginRequest);

        $userPassword = new UserPassword(new NotEmptyString($user->getPassword()));

        if (
            $user->isVerify() &&
            $this->passwordHasher->isPasswordValid($userPassword, $loginRequest->password)
        ) {
            /* @see LoginUserListener::__invoke() */
            $this->dispatcher->dispatch(new LoginUserEvent(new PositiveInt($user->getId())));

            return $user;
        }

        throw $this->getAuthException();
    }

    private function getUser(LoginRequest $loginRequest): ReadUser
    {
        $email = new Email($loginRequest->email);
        $readUser = $this->userRepository->findByEmail($email);

        if ($readUser === null) {
            throw $this->getAuthException();
        }

        return $readUser;
    }

    /**
     * @throw AuthenticationException
     */
    private function getAuthException(): AuthenticationException
    {
        return new AuthenticationException(
            ErrorCode::LOGIN_INCORRECT_EMAIL_OR_PASSWORD,
            'User with given email and password not found'
        );
    }
}
