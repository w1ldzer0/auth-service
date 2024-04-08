<?php

declare(strict_types=1);

namespace App\Shared\EventListener\Exception;

use App\Shared\Response\JsonResponseBuilder;
use App\Shared\Response\ResponseField\ErrorMessageField;
use App\Shared\Util\DevChecker;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(ExceptionEvent::class, priority: -100)]
final class DefaultExceptionHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly DevChecker $devChecker,
    ) {
    }

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        if ($this->devChecker->isDev()) {
            return;
        }

        $exception = $exceptionEvent->getThrowable();
        $this->logger->error($exception);

        $response = JsonResponseBuilder::create()
            ->addField(new ErrorMessageField('A system error has occurred contact your administrator'))
            ->setHttpStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->build();

        $exceptionEvent->setResponse($response);
    }
}
