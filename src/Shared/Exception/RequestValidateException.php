<?php

declare(strict_types=1);

namespace App\Shared\Exception;

use App\Shared\Response\ErrorCode;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RequestValidateException extends HttpException
{
    public function __construct(
        private readonly ErrorCode $errorCode,
        private readonly array $errors,
        private readonly array $groups = []
    ) {
        parent::__construct(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'Data Validation Exception: ' . json_encode($errors)
        );
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getErrorCode(): ErrorCode
    {
        return $this->errorCode;
    }
}
