<?php

declare(strict_types=1);

namespace App\User\ApiClient\Request;

use App\Client\ApiClient\Enum\HttpMethod;
use App\Client\ApiClient\Request\AbstractRequest;
use App\Shared\ValueObject\Email;
use App\Shared\ValueObject\NotEmptyString;
use App\User\ApiClient\UserField;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_3;

class CreateUserRequest extends AbstractRequest
{
    public function __construct(
        private readonly Email $email,
        private readonly NotEmptyString $password,
        private readonly ?ISO3166_1_Alpha_3 $country = null,
        private readonly ?array $additionalContacts = null,
    ) {
    }

    public function getMethod(): string
    {
        return HttpMethod::POST;
    }

    public function getUri(): string
    {
        return '/admin/users';
    }

    public function getRequestBody(): array
    {
        return [
            UserField::EMAIL => $this->email->getValue(),
            UserField::PASSWORD => $this->password->getValue(),
            UserField::COUNTRY => $this->country?->value,
            UserField::ADDITIONAL_CONTACTS => $this->additionalContacts,
        ];
    }
}
