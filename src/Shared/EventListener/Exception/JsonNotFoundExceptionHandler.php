<?php

declare(strict_types=1);

namespace App\Shared\EventListener\Exception;

use App\Shared\Response\ErrorCode;
use App\Shared\Response\JsonResponseBuilder;
use App\Shared\Response\ResponseField\ErrorCodeField;
use App\Shared\Response\ResponseField\ErrorMessageField;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener(ExceptionEvent::class, priority: 100)]
final class JsonNotFoundExceptionHandler
{
    public function __construct(
    ) {
    }

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $exception = $exceptionEvent->getThrowable();

        if (!$exception instanceof NotFoundHttpException) {
            return;
        }

        $response = JsonResponseBuilder::create()
            ->addField(new ErrorCodeField(ErrorCode::PAGE_NOT_FOUND))
            ->addField(new ErrorMessageField($exception->getMessage()))
            ->setHttpStatusCode(Response::HTTP_NOT_FOUND)
            ->build();

        $exceptionEvent->setResponse($response);
    }
}
