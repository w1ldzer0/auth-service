<?php

declare(strict_types=1);

namespace App\User\ApiClient\Request;

use App\Client\ApiClient\Enum\HttpMethod;
use App\Client\ApiClient\Request\AbstractRequest;

class FindByEmailUserRequest extends AbstractRequest
{
    public function __construct(
        private readonly string $email,
    ) {
    }

    public function getMethod(): string
    {
        return HttpMethod::GET;
    }

    public function getUri(): string
    {
        return '/admin/users/' . $this->email;
    }
}
