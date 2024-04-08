<?php

declare(strict_types=1);

namespace App\Auth\Register\Listener;

use App\Auth\Register\Event\UserRegisteredEvent;
use App\Auth\Register\Service\VerifyCodeService;

// off task https://alfatech2020.atlassian.net/browse/PLAT-391
class UserRegisteredListener
{
    public function __construct(
        private readonly VerifyCodeService $verifyCodeService
    ) {
    }

    public function __invoke(UserRegisteredEvent $event): void
    {
        $this->verifyCodeService->create($event->getUserId());
    }
}
