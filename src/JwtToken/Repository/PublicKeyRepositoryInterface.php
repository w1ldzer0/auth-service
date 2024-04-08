<?php

declare(strict_types=1);

namespace App\JwtToken\Repository;

use App\Shared\ValueObject\NotEmptyString;

interface PublicKeyRepositoryInterface
{
    public function get(): NotEmptyString;
}
