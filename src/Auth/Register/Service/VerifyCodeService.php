<?php

declare(strict_types=1);

namespace App\Auth\Register\Service;

use App\Auth\Register\Event\VerifyCodeCreatedEvent;
use App\Auth\Register\Repository\VerifyCodeRepositoryInterface;
use App\Notification\Listener\VerifyCodeCreatedListener;
use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
use Psr\EventDispatcher\EventDispatcherInterface;

class VerifyCodeService
{
    public function __construct(
        private readonly VerifyCodeGenerator $codeGenerator,
        private readonly VerifyCodeRepositoryInterface $verifyCodeRepository,
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    public function create(PositiveInt $userId): NotEmptyString
    {
        $code = $this->codeGenerator->generate();

        $this->verifyCodeRepository->save(
            new NotEmptyString((string) $userId),
            $code
        );

        /* @see VerifyCodeCreatedListener::__invoke() */
        $this->dispatcher->dispatch(new VerifyCodeCreatedEvent($userId, $code));

        return $code;
    }
}
