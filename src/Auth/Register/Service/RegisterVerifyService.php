<?php

declare(strict_types=1);

namespace App\Auth\Register\Service;

use App\Auth\Register\Event\VerifyCodeValidatedEvent;
use App\Auth\Register\Listener\VerifyCodeValidatedListener;
use App\Auth\Register\Repository\VerifyCodeRepositoryInterface;
use App\Auth\Register\Request\RegisterVerifyRequest;
use App\Shared\Exception\NotFoundException;
use App\Shared\Exception\NotValidateCodeException;
use App\Shared\Response\ErrorCode;
use App\Shared\Util\DevChecker;
use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
use App\User\Model\ReadUser;
use App\User\Repository\UserRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class RegisterVerifyService
{
    private const CODE_FOR_DEV = '666666';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly DevChecker $devChecker,
        private readonly VerifyCodeRepositoryInterface $verifyCodeRepository,
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function verify(RegisterVerifyRequest $request): ReadUser
    {
        $user = $this->getUser($request);

        $this->validateCode(
            new PositiveInt($user->getId()),
            new NotEmptyString($request->getCode())
        );

        /* @see VerifyCodeValidatedListener::__invoke() */
        $this->dispatcher->dispatch(new VerifyCodeValidatedEvent(new PositiveInt($user->getId())));

        return $user;
    }

    /**
     * @throws NotFoundException
     */
    private function getUser(RegisterVerifyRequest $request): ReadUser
    {
        return $this->userRepository->getById($request->getUserId());
    }

    /**
     * @throws NotValidateCodeException
     */
    private function validateCode(PositiveInt $userId, NotEmptyString $code): void
    {
        try {
            $currentCode = $this->verifyCodeRepository->get(
                new NotEmptyString((string) $userId)
            );
        } catch (NotFoundException) {
            throw $this->exception();
        }

        if ($currentCode->equal($code)) {
            return;
        }

        $this->checkDevCode($code);
    }

    /**
     * @throws NotValidateCodeException
     */
    private function checkDevCode(NotEmptyString $code): void
    {
        if (
            $this->devChecker->isDev() &&
            $code->getValue() === self::CODE_FOR_DEV
        ) {
            return;
        }

        throw $this->exception();
    }

    private function exception(): NotValidateCodeException
    {
        return new NotValidateCodeException(
            ErrorCode::NOT_VALIDATE_CODE_VERIFY,
            'Verification code is not correct'
        );
    }
}
