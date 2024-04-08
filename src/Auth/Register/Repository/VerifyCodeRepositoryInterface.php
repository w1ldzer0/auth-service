<?php

declare(strict_types=1);

namespace App\Auth\Register\Repository;

use App\Shared\Exception\NotFoundException;
use App\Shared\ValueObject\NotEmptyString;

interface VerifyCodeRepositoryInterface
{
    /**
     * @throws NotFoundException
     */
    public function get(NotEmptyString $userIdentify): NotEmptyString;

    public function save(NotEmptyString $userIdentify, NotEmptyString $verifyCode): void;

    public function remove(NotEmptyString $userIdentify): void;
}
