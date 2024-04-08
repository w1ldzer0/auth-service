<?php

declare(strict_types=1);

namespace App\Auth\Password\Event;

class RecoveryPasswordCodeValidatedEvent
{
    public function __construct(
        private readonly int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
