<?php

declare(strict_types=1);

namespace App\Shared\EventListener\Exception;

use App\Shared\Exception\AuthenticationException;
use App\Shared\Response\JsonResponseBuilder;
use App\Shared\Response\ResponseField\ErrorCodeField;
use App\Shared\Response\ResponseField\ErrorMessageField;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(ExceptionEvent::class, priority: 100)]
final class AuthenticationExceptionHandler
{
    public function __construct(
    ) {
    }

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $exception = $exceptionEvent->getThrowable();

        if (!$exception instanceof AuthenticationException) {
            return;
        }

        $response = JsonResponseBuilder::create()
            ->addField(new ErrorCodeField($exception->getErrorCode()))
            ->addField(new ErrorMessageField($exception->getMessage()))
            ->setHttpStatusCode(Response::HTTP_UNAUTHORIZED)
            ->build();

        $exceptionEvent->setResponse($response);
    }
}
