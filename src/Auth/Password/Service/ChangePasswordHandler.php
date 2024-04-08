<?php

declare(strict_types=1);

namespace App\Auth\Password\Service;

use App\Auth\Password\Event\RecoveryPasswordCodeValidatedEvent;
use App\Auth\Password\Model\ChangePasswordCommand;
use App\Shared\Exception\NotValidateCodeException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ChangePasswordHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly RecoveryPasswordCodeService $recoveryPasswordCodeService,
        private readonly UpdatePasswordService $updatePasswordService,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @throws NotValidateCodeException
     */
    public function __invoke(ChangePasswordCommand $changePasswordCommand): void
    {
        $recoveryPasswordCode = $changePasswordCommand->getCode();
        $this->recoveryPasswordCodeService->validate($recoveryPasswordCode);
        $userId = $this->recoveryPasswordCodeService->getUserId($recoveryPasswordCode);

        $this->dispatcher->dispatch(new RecoveryPasswordCodeValidatedEvent($userId));

        $this->updatePasswordService->update($userId, $changePasswordCommand->getPassword());
    }
}
