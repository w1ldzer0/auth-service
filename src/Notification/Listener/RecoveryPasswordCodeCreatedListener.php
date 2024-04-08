<?php

declare(strict_types=1);

namespace App\Notification\Listener;

use App\Auth\Password\Event\RecoveryPasswordCodeCreatedEvent;
use App\Notification\Message\EmailMessage;
use App\Shared\ValueObject\NotEmptyString;
use Assert\Assertion;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(RecoveryPasswordCodeCreatedEvent::class, priority: -10)]
class RecoveryPasswordCodeCreatedListener
{
    public const RECOVERY_PASSWORD = 'recovery_password';
    public const RECOVERY_PASSWORD_URL = 'recovery_password_url';

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function __invoke(RecoveryPasswordCodeCreatedEvent $event): void
    {
        $this->messageBus->dispatch(new EmailMessage(
            self::RECOVERY_PASSWORD,
            [
                'user_id' => $event->getUserId(),
                'recovery_url' => $this->getRecoveryUrl($event->getRecoveryCode()),
            ]
        ));
    }

    private function getRecoveryUrl(NotEmptyString $code): string
    {
        $recoveryUrl = $this->parameterBag->get(self::RECOVERY_PASSWORD_URL);
        Assertion::notNull($recoveryUrl);

        return $recoveryUrl . $code->getValue();
    }
}
