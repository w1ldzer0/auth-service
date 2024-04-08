<?php

declare(strict_types=1);

namespace App\Shared\Traits;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;

trait CommandDispatchTrait
{
    use HandleTrait {
        HandleTrait::handle as parentHandler;
    }

    /**
     * @psalm-suppress InvalidThrow
     */
    private function handler(object $message): mixed
    {
        try {
            return $this->parentHandler($message);
        } catch (HandlerFailedException $exception) {
            while ($exception instanceof HandlerFailedException) {
                $exception = $exception->getPrevious();
            }

            throw $exception;
        }
    }
}
