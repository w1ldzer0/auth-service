<?php

declare(strict_types=1);

namespace App\Auth\Register\Listener;

use App\Auth\Register\Event\VerifyCodeValidatedEvent;
use App\Auth\Register\Repository\VerifyCodeRepositoryInterface;
use App\Shared\Exception\NotValidateCodeException;
use App\Shared\ValueObject\NotEmptyString;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(VerifyCodeValidatedEvent::class)]
class VerifyCodeValidatedListener
{
    public function __construct(
        private readonly VerifyCodeRepositoryInterface $verifyCodeRepository,
    ) {
    }

    /**
     * @throws NotValidateCodeException
     */
    public function __invoke(VerifyCodeValidatedEvent $event): void
    {
        $this->verifyCodeRepository->remove(new NotEmptyString((string) $event->getUserId()));
    }
}
