<?php

declare(strict_types=1);

namespace App\Notification\Message;

class EmailMessage
{
    public function __construct(
        private readonly string $type,
        private readonly array $payload
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
