<?php

declare(strict_types=1);

namespace App\Auth\Register\Request;

use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Contracts\Service\Attribute\Required;

class RegisterRequest
{
    public function __construct(
        #[Required]
        #[NotBlank]
        #[Email]
        private readonly string $email,
        #[Required]
        #[NotBlank]
        #[Country(alpha3: true)]
        private readonly string $country,
        private readonly string $password,
        #[Valid(traverse: true)]
        private readonly ?AdditionalContactRequest $additionalContact = null
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAdditionalContact(): ?AdditionalContactRequest
    {
        return $this->additionalContact ?? null;
    }
}
