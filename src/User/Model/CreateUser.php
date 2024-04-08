<?php

declare(strict_types=1);

namespace App\User\Model;

use App\Shared\ValueObject\Email;
use App\Shared\ValueObject\NotEmptyString;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_3;

class CreateUser
{
    public function __construct(
        private readonly Email $email,
        private readonly NotEmptyString $password,
        private readonly ?ISO3166_1_Alpha_3 $country = null,
        private readonly ?array $additionalContacts = null,
    ) {
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): NotEmptyString
    {
        return $this->password;
    }

    public function getCountry(): ?ISO3166_1_Alpha_3
    {
        return $this->country;
    }

    public function getAdditionalContacts(): ?array
    {
        return $this->additionalContacts;
    }
}
