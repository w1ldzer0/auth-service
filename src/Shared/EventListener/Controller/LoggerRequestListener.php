<?php

declare(strict_types=1);

namespace App\Shared\EventListener\Controller;

use App\Shared\Util\DevChecker;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

#[AsEventListener(ControllerEvent::class)]
class LoggerRequestListener
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly DevChecker $devChecker
    ) {
    }

    public function __invoke(ControllerEvent $event): void
    {
        if (!$this->devChecker->isDev()) {
            return;
        }

        $request = $event->getRequest();
        $dateTime = new DateTimeImmutable();

        $log = sprintf('[request]: [%s] uri: [%s] method: [%s] headers: [%s] body: [%s]',
            $dateTime->format(DateTimeImmutable::ATOM),
            $request->getUri(),
            $request->getMethod(),
            json_encode($request->headers->all()),
            empty($request->getContent()) ? '' : json_encode($request->toArray())
        );

        $this->logger->info($log);
    }
}
