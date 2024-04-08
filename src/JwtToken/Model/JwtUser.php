<?php

declare(strict_types=1);

namespace App\JwtToken\Model;

use App\Shared\ValueObject\PositiveInt;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtUser implements UserInterface
{
    public function __construct(
        private readonly PositiveInt $id,
    ) {
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function getId(): PositiveInt
    {
        return $this->id;
    }
}
