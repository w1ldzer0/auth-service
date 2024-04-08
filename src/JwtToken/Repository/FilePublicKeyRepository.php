<?php

declare(strict_types=1);

namespace App\JwtToken\Repository;

use App\Shared\ValueObject\NotEmptyString;
use Assert\Assertion;
use Assert\AssertionFailedException;
use InvalidArgumentException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\KeyLoader\RawKeyLoader;

class FilePublicKeyRepository implements PublicKeyRepositoryInterface
{
    public function __construct(
        private readonly RawKeyLoader $keyLoader
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws AssertionFailedException
     */
    public function get(): NotEmptyString
    {
        /** @psalm-suppress InternalMethod */
        $publicKey = $this->keyLoader->getPublicKey();

        Assertion::string($publicKey);

        return new NotEmptyString($publicKey);
    }
}
