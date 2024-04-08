<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\Shared\Exception\NotFoundException;
use App\Shared\ValueObject\Email;
use App\User\Model\CreateUser;
use App\User\Model\ReadUser;
use App\User\Model\UpdateUser;

interface UserRepositoryInterface
{
    /**
     * @throws NotFoundException
     */
    public function getById(int $id): ReadUser;

    /**
     * @throws NotFoundException
     */
    public function getByEmail(Email $email): ReadUser;

    public function findById(int $id): ?ReadUser;

    public function findByEmail(Email $email): ?ReadUser;

    public function create(CreateUser $user): ReadUser;

    public function update(UpdateUser $user): void;
}
