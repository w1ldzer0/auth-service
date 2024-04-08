<?php

declare(strict_types=1);

namespace App\User\ApiClient;

use App\Client\ApiClient\ApiClient;
use App\Shared\Exception\ExternalServerException;
use App\Shared\Exception\NotFoundException;
use App\Shared\Exception\UniqueConstraintViolationException;
use App\User\ApiClient\Request\CreateUserRequest;
use App\User\ApiClient\Request\FindByEmailUserRequest;
use App\User\ApiClient\Request\FindByIdUserRequest;
use App\User\ApiClient\Request\UpdateUserRequest;
use Assert\Assertion;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserApiClient
{
    private readonly ApiClient $apiClient;

    public function __construct(
        HttpClientInterface $userClient,
    ) {
        $this->apiClient = new ApiClient($userClient);
    }

    /**
     * @throws ExternalServerException
     * @throws UniqueConstraintViolationException
     * @throws NotFoundException
     */
    public function create(CreateUserRequest $request): array
    {
        $response = $this->apiClient->sendRequest($request);

        Assertion::notEmpty($response);

        return $response;
    }

    /**
     * @throws ExternalServerException
     * @throws NotFoundException
     */
    public function findById(FindByIdUserRequest $request): array
    {
        try {
            return $this->apiClient->sendRequest($request);
        } catch (UniqueConstraintViolationException $e) {
            throw new ExternalServerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ExternalServerException
     * @throws NotFoundException
     */
    public function findByEmail(FindByEmailUserRequest $request): array
    {
        try {
            return $this->apiClient->sendRequest($request);
        } catch (UniqueConstraintViolationException $e) {
            throw new ExternalServerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ExternalServerException
     * @throws NotFoundException
     * @throws UniqueConstraintViolationException
     */
    public function update(UpdateUserRequest $request): void
    {
        $this->apiClient->sendRequest($request);
    }
}
