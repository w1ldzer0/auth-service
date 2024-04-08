<?php

declare(strict_types=1);

namespace App\Client\Google\Oauth;

use Assert\Assertion;
use League\OAuth2\Client\Provider\Google;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleOauth2ClientFactory
{
    private const OAUTH_PARAMETER = 'oauth';
    private const GOOGLE_PARAMETER = 'google';

    private const CLIENT_ID_FIELD = 'clientId';
    private const CLIENT_SECRET_FIELD = 'clientSecret';
    private const REDIRECT_URI_FIELD = 'redirectUri';

    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function make(): Google
    {
        return new Google([
            self::CLIENT_ID_FIELD => $this->getParameter(self::CLIENT_ID_FIELD),
            self::CLIENT_SECRET_FIELD => $this->getParameter(self::CLIENT_SECRET_FIELD),
            self::REDIRECT_URI_FIELD => $this->getParameter(self::REDIRECT_URI_FIELD),
        ]);
    }

    private function getParameter(string $name): string
    {
        $oauthParameter = $this->parameterBag->get(self::OAUTH_PARAMETER);
        Assertion::notNull($oauthParameter);
        Assertion::isArray($oauthParameter);
        $value = $oauthParameter[self::GOOGLE_PARAMETER][$name] ?? null;
        Assertion::notNull($value);
        Assertion::string($value);

        return $value;
    }
}
