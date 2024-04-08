<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\Shared\Exception\ExternalServerException;
use App\Shared\Exception\NotFoundException;
use App\Shared\Exception\UniqueConstraintViolationException;
use App\Shared\Response\ErrorCode;
use App\Shared\ValueObject\Email;
use App\User\ApiClient\Request\CreateUserRequest;
use App\User\ApiClient\Request\FindByEmailUserRequest;
use App\User\ApiClient\Request\FindByIdUserRequest;
use App\User\ApiClient\Request\UpdateUserRequest;
use App\User\ApiClient\UserApiClient;
use App\User\Factory\ReadFactory;
use App\User\Model\CreateUser;
use App\User\Model\ReadUser;
use App\User\Model\UpdateUser;

class ApiUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly UserApiClient $userApiClient,
        private readonly ReadFactory $findUserFactory,
    ) {
    }

    /**
     * @throws ExternalServerException
     * @throws NotFoundException
     */
    public function getById(int $id): ReadUser
    {
        $response = $this->userApiClient->findById(
            new FindByIdUserRequest($id)
        );

        return $this->findUserFactory->make($response);
    }

    /**
     * @throws ExternalServerException
     * @throws UniqueConstraintViolationException
     * @throws NotFoundException
     */
    public function create(CreateUser $user): ReadUser
    {
        try {
            $response = $this->userApiClient->create(
                new CreateUserRequest(
                    $user->getEmail(),
                    $user->getPassword(),
                    $user->getCountry(),
                    $user->getAdditionalContacts()
                )
            );
        } catch (UniqueConstraintViolationException $e) {
            throw new UniqueConstraintViolationException(
                ErrorCode::USER_UNIQUE_KEY_EXIST,
                'User with this email exists',
                $e->getCode(),
                $e
            );
        }

        return $this->findUserFactory->make($response);
    }

    /**
     * @throws ExternalServerException
     * @throws NotFoundException
     * @throws UniqueConstraintViolationException
     */
    public function update(UpdateUser $user): void
    {
        try {
            $this->userApiClient->update(
                (new UpdateUserRequest($user->getId()))
                    ->setFirstName($user->getFirstName())
                    ->setLastName($user->getLastName())
                    ->setEmail($user->getEmail())
                    ->setPassword($user->getPassword())
                    ->setCountry($user->getCountry())
                    ->setIndustry($user->getIndustry())
                    ->setBudget($user->getBudget())
                    ->setAdditionalContacts($user->getAdditionalContacts())
                    ->setIsVerify($user->getIsVerify())
                    ->setLastLogin($user->getLastLogin())
                    ->setSite($user->getSite())
                    ->setPhone($user->getPhone())
            );
        } catch (UniqueConstraintViolationException $e) {
            throw new UniqueConstraintViolationException(
                ErrorCode::USER_UNIQUE_KEY_EXIST,
                'User with this email exists',
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @throws ExternalServerException
     * @throws NotFoundException
     */
    public function getByEmail(Email $email): ReadUser
    {
        $response = $this->userApiClient->findByEmail(
            new FindByEmailUserRequest($email->getValue())
        );

        return $this->findUserFactory->make($response);
    }

    /**
     * @throws ExternalServerException
     */
    public function findById(int $id): ?ReadUser
    {
        try {
            return $this->getById($id);
        } catch (NotFoundException) {
            return null;
        }
    }

    /**
     * @throws ExternalServerException
     */
    public function findByEmail(Email $email): ?ReadUser
    {
        try {
            return $this->getByEmail($email);
        } catch (NotFoundException) {
            return null;
        }
    }
}
