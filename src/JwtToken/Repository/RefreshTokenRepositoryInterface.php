<?php

declare(strict_types=1);

namespace App\JwtToken\Repository;

use App\Shared\Exception\NotFoundException;
use App\Shared\ValueObject\NotEmptyString;

interface RefreshTokenRepositoryInterface
{
    /**
     * @throws NotFoundException
     */
    public function get(NotEmptyString $key): NotEmptyString;

    public function save(NotEmptyString $key, NotEmptyString $refreshToken): void;

    public function remove(NotEmptyString $key): void;
}
