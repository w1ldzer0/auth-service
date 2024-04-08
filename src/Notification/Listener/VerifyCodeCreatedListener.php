<?php

declare(strict_types=1);

namespace App\Notification\Listener;

use App\Auth\Register\Event\VerifyCodeCreatedEvent;
use App\Notification\Message\EmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

// off task https://alfatech2020.atlassian.net/browse/PLAT-391
class VerifyCodeCreatedListener
{
    public const CODE_EMAIL_VERIFY = 'code_email_verify';

    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(VerifyCodeCreatedEvent $event): void
    {
        $this->messageBus->dispatch(new EmailMessage(
            self::CODE_EMAIL_VERIFY,
            [
                'user_id' => $event->getUserId()->getValue(),
                'code' => $event->getVerifyCode()->getValue(),
            ]
        ));
    }
}
