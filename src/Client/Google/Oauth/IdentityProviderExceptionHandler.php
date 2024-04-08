<?php

declare(strict_types=1);

namespace App\Client\Google\Oauth;

use App\Client\Google\Oauth\Exception\GoogleServiceException;
use App\Client\Google\Oauth\Exception\InvalidGrandException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class IdentityProviderExceptionHandler
{
    private const ERROR_FIELD = 'error';
    private const INVALID_GRANT_ERROR = 'invalid_grant';

    public function handler(IdentityProviderException $exception): GoogleServiceException
    {
        $responseBody = $exception->getResponseBody();

        if (
            isset($responseBody[self::ERROR_FIELD]) &&
            $responseBody[self::ERROR_FIELD] === self::INVALID_GRANT_ERROR
        ) {
            return new InvalidGrandException('Code not valid');
        }

        return new GoogleServiceException(
            'Error for get google auth-token: ' . $exception->getMessage(),
            $exception->getCode(),
            $exception
        );
    }
}
