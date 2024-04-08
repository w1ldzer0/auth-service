<?php

declare(strict_types=1);

namespace App\Auth\Register\Listener;

use App\Auth\Register\Event\UpdateRegisterEmailEvent;
use App\Auth\Register\Service\VerifyCodeService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(UpdateRegisterEmailEvent::class)]
class UpdateRegisterEmailListener
{
    public function __construct(
        private readonly VerifyCodeService $verifyCodeService
    ) {
    }

    public function __invoke(UpdateRegisterEmailEvent $event): void
    {
        $this->verifyCodeService->create($event->getUserId());
    }
}
