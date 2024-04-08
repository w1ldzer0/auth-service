<?php

declare(strict_types=1);

namespace App\User\Model;

use Symfony\Component\Security\Core\User\UserInterface;

class ReadUser implements UserInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $password,
        private readonly string $email,
        private readonly bool $isVerify,
        private readonly ?string $country = null,
        private readonly ?string $firstName = null,
        private readonly ?string $lastName = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): array
    {
        return [
          'password',
        ];
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->getId();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function isVerify(): bool
    {
        return $this->isVerify;
    }
}
