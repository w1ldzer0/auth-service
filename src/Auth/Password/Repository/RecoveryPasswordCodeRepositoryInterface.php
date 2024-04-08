<?php

declare(strict_types=1);

namespace App\Auth\Password\Repository;

use App\Shared\Exception\NotFoundException;
use App\Shared\ValueObject\NotEmptyString;

interface RecoveryPasswordCodeRepositoryInterface
{
    /**
     * @throws NotFoundException
     */
    public function get(NotEmptyString $key): NotEmptyString;

    public function save(NotEmptyString $key, NotEmptyString $value): void;

    public function remove(NotEmptyString $key): void;
}
