<?php

declare(strict_types=1);

namespace App\Auth\Register\Service;

use App\Auth\Register\Model\ResendCodeVerifyCommand;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ResendCodeVerifyHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly VerifyCodeService $verifyCodeService
    ) {
    }

    public function __invoke(ResendCodeVerifyCommand $resendCodeVerifyCommand)
    {
        $this->verifyCodeService->create($resendCodeVerifyCommand->getUserId());
    }
}
