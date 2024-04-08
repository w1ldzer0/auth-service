<?php

declare(strict_types=1);

namespace App\Client\ApiClient\Request;

abstract class AbstractRequest
{
    abstract public function getMethod(): string;

    abstract public function getUri(): string;

    public function getQuery(): array
    {
        return [];
    }

    public function getRequestBody(): array
    {
        return [];
    }

    public function getHeader(): array
    {
        return [];
    }
}
