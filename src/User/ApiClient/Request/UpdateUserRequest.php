<?php

declare(strict_types=1);

namespace App\User\ApiClient\Request;

use App\Client\ApiClient\Enum\HttpMethod;
use App\Client\ApiClient\Request\AbstractRequest;
use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
use App\User\ApiClient\UserField;
use App\User\Enum\Budget;
use App\User\Enum\Industry;
use DateTimeImmutable;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_3;

class UpdateUserRequest extends AbstractRequest
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

    public function getMethod(): string
    {
        return HttpMethod::PATCH;
    }

    public function getUri(): string
    {
        return '/admin/users/' . $this->id->getValue();
    }

    public function getRequestBody(): array
    {
        return array_filter([
            UserField::FIRST_NAME => $this->firstName?->getValue(),
            UserField::LAST_NAME => $this->lastName?->getValue(),
            UserField::EMAIL => $this->email?->getValue(),
            UserField::PASSWORD => $this->password?->getValue(),
            UserField::COUNTRY => $this->country?->value,
            UserField::BUDGET => $this->budget?->value,
            UserField::INDUSTRY => $this->industry?->value,
            UserField::ADDITIONAL_CONTACTS => $this->additionalContacts,
            UserField::IS_VERIFY => $this->isVerify,
            UserField::LAST_LOGIN => $this->lastLogin?->format(DateTimeImmutable::RFC3339_EXTENDED),
            UserField::PHONE => $this->phone?->getValue(),
            UserField::SITE => $this->site?->getValue(),
        ]);
    }

    public function setEmail(?NotEmptyString $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(?NotEmptyString $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getIsVerify(): ?bool
    {
        return $this->isVerify;
    }

    public function setIsVerify(?bool $isVerify): static
    {
        $this->isVerify = $isVerify;

        return $this;
    }

    public function setLastLogin(?DateTimeImmutable $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function setFirstName(?NotEmptyString $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(?NotEmptyString $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setCountry(?ISO3166_1_Alpha_3 $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function setIndustry(?Industry $industry): static
    {
        $this->industry = $industry;

        return $this;
    }

    public function setBudget(?Budget $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function setAdditionalContacts(?array $additionalContacts): static
    {
        $this->additionalContacts = $additionalContacts;

        return $this;
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
