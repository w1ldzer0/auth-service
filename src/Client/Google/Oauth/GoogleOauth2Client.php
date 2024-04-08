<?php

declare(strict_types=1);

namespace App\Client\Google\Oauth;

use App\Client\Google\Oauth\Exception\GoogleServiceException;
use App\Client\Google\Oauth\Exception\InvalidGrandException;
use App\Shared\ValueObject\NotEmptyString;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;

class GoogleOauth2Client
{
    private const ACCESS_TOKEN = 'access_token';
    private const CODE_FIELD = 'code';
    private const AUTHORIZATION_CODE_GRANT = 'authorization_code';

    private Google $provider;

    public function __construct(
        GoogleOauth2ClientFactory $oauth2ClientFactory,
        private readonly UserFactory $userFactory,
        private readonly IdentityProviderExceptionHandler $exceptionHandler
    ) {
        $this->provider = $oauth2ClientFactory->make();
    }

    /**
     * @throws InvalidGrandException
     * @throws GoogleServiceException
     */
    public function getUser(NotEmptyString $code): User
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->provider->getResourceOwner(
            new AccessToken([self::ACCESS_TOKEN => $this->getAccessToken($code)])
        );

        return $this->userFactory->make($googleUser);
    }

    /**
     * @throws InvalidGrandException
     * @throws GoogleServiceException
     */
    private function getAccessToken(NotEmptyString $code): string
    {
        try {
            $accessToken = $this->provider->getAccessToken(self::AUTHORIZATION_CODE_GRANT, [
                self::CODE_FIELD => $code->getValue(),
            ]);
        } catch (IdentityProviderException $e) {
            throw $this->exceptionHandler->handler($e);
        }

        return $accessToken->getToken();
    }
}
