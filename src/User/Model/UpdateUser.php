<?php

declare(strict_types=1);

namespace App\User\Model;

use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
use App\User\Enum\Budget;
use App\User\Enum\Industry;
use DateTimeImmutable;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_3;

class UpdateUser
{
    private ?NotEmptyString $firstName = null;
    private ?NotEmptyString $lastName = null;
    private ?NotEmptyString $email = null;
    private ?NotEmptyString $password = null;
    private ?ISO3166_1_Alpha_3 $country = null;
    private ?Industry $industry = null;
    private ?Budget $budget = null;
    private ?array $additionalContacts = null;
    private ?NotEmptyString $phone = null;
    private ?bool $isVerify = null;
    private ?DateTimeImmutable $lastLogin = null;
    private ?NotEmptyString $site = null;

    public function __construct(
        private readonly PositiveInt $id
    ) {
    }

    public function getId(): PositiveInt
    {
        return $this->id;
    }

    public function getEmail(): ?NotEmptyString
    {
        return $this->email;
    }

    public function setEmail(NotEmptyString $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(?NotEmptyString $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): ?NotEmptyString
    {
        return $this->password;
    }

    public function getIsVerify(): ?bool
    {
        return $this->isVerify;
    }

    public function setIsVerify(bool $isVerify): static
    {
        $this->isVerify = $isVerify;

        return $this;
    }

    public function getLastLogin(): ?DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?DateTimeImmutable $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getFirstName(): ?NotEmptyString
    {
        return $this->firstName;
    }

    public function setFirstName(?NotEmptyString $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?NotEmptyString
    {
        return $this->lastName;
    }

    public function setLastName(?NotEmptyString $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCountry(): ?ISO3166_1_Alpha_3
    {
        return $this->country;
    }

    public function setCountry(?ISO3166_1_Alpha_3 $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getIndustry(): ?Industry
    {
        return $this->industry;
    }

    public function setIndustry(?Industry $industry): static
    {
        $this->industry = $industry;

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getAdditionalContacts(): ?array
    {
        return $this->additionalContacts;
    }

    public function setAdditionalContacts(?array $additionalContacts): static
    {
        $this->additionalContacts = $additionalContacts;

        return $this;
    }

    public function getPhone(): ?NotEmptyString
    {
        return $this->phone;
    }

    public function setPhone(?NotEmptyString $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSite(): ?NotEmptyString
    {
        return $this->site;
    }

    public function setSite(?NotEmptyString $site): static
    {
        $this->site = $site;

        return $this;
    }
}
