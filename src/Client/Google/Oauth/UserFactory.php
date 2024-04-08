<?php

declare(strict_types=1);

namespace App\Client\Google\Oauth;

use App\Shared\ValueObject\Email;
use App\Shared\ValueObject\NotEmptyString;
use League\OAuth2\Client\Provider\GoogleUser;

class UserFactory
{
    public function make(GoogleUser $googleUser): User
    {
        $googleId = (string) $googleUser->getId();
        $email = $googleUser->getEmail();

        return new User(
            new NotEmptyString($googleId),
            $email === null ? null : new Email($email)
        );
    }
}
