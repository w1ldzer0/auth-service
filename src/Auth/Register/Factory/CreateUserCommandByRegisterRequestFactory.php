<?php

declare(strict_types=1);

namespace App\Auth\Register\Factory;

use App\Auth\Register\Request\RegisterRequest;
use App\Shared\ValueObject\Email;
use App\Shared\ValueObject\NotEmptyString;
use App\User\Model\CreateUserCommand;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_3;

class CreateUserCommandByRegisterRequestFactory
{
    public static function make(RegisterRequest $registerRequest): CreateUserCommand
    {
        return new CreateUserCommand(
            new Email($registerRequest->getEmail()),
            new NotEmptyString($registerRequest->getPassword()),
            ISO3166_1_Alpha_3::from($registerRequest->getCountry()),
            self::getAdditionalContacts($registerRequest)
        );
    }

    private static function getAdditionalContacts(RegisterRequest $registerRequest): ?array
    {
        $additionalContact = $registerRequest->getAdditionalContact();

        if ($additionalContact === null) {
            return null;
        }

        return [
            $additionalContact->getType() => $additionalContact->getContact(),
        ];
    }
}
