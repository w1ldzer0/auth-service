<?php

declare(strict_types=1);

namespace App\Shared\EventListener\Exception;

use App\Shared\Exception\UniqueConstraintViolationException;
use App\Shared\Response\ErrorCode;
use App\Shared\Response\JsonResponseBuilder;
use App\Shared\Response\ResponseField\ErrorCodeField;
use App\Shared\Response\ResponseField\ErrorMessageField;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(ExceptionEvent::class, priority: 100)]
final class UniqueConstraintViolationExceptionHandler
{
    public function __construct(
    ) {
    }

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $exception = $exceptionEvent->getThrowable();

        if (!$exception instanceof UniqueConstraintViolationException) {
            return;
        }

        $response = JsonResponseBuilder::create()
            ->addField(new ErrorMessageField($exception->getMessage()))
            ->addField(new ErrorCodeField(ErrorCode::USER_UNIQUE_KEY_EXIST))
            ->setHttpStatusCode(Response::HTTP_CONFLICT)
            ->build();

        $exceptionEvent->setResponse($response);
    }
}
