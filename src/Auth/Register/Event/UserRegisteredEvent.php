<?php

declare(strict_types=1);

namespace App\Auth\Register\Event;

use App\Shared\ValueObject\PositiveInt;
use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
    public function __construct(
        private readonly PositiveInt $userId
    ) {
    }

    public function getUserId(): PositiveInt
    {
        return $this->userId;
    }
}
