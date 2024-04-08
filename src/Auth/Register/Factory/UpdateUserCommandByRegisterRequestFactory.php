<?php

declare(strict_types=1);

namespace App\Auth\Register\Factory;

use App\Auth\Register\Request\RegisterRequest;
use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
use App\User\Model\UpdateUserCommand;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_3;

class UpdateUserCommandByRegisterRequestFactory
{
    public static function make(
        PositiveInt $userId,
        RegisterRequest $registerRequest
    ): UpdateUserCommand {
        return (new UpdateUserCommand($userId))
            ->setEmail(new NotEmptyString($registerRequest->getEmail()))
            ->setPassword(new NotEmptyString($registerRequest->getPassword()))
            ->setCountry(ISO3166_1_Alpha_3::from($registerRequest->getCountry()));
    }
}
