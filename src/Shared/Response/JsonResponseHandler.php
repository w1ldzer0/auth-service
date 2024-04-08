<?php

declare(strict_types=1);

namespace App\Shared\Response;

use App\Shared\Response\ResponseField\PayloadField;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

#[AsEventListener(ViewEvent::class)]
final class JsonResponseHandler
{
    private const SUPPORTED_REQUEST_FORMAT = 'json';

    public function __construct()
    {
    }

    public function __invoke(ViewEvent $viewEvent): void
    {
        if (!$this->support($viewEvent)) {
            return;
        }

        /** @psalm-suppress MixedAssignment */
        $controllerResponse = $viewEvent->getControllerResult();

        if ($controllerResponse instanceof JsonResponse) {
            return;
        }

        $jsonResponse = JsonResponseBuilder::create()
            ->addField(new PayloadField($controllerResponse))
            ->setHttpStatusCode(
                $controllerResponse === null ?
                    Response::HTTP_NO_CONTENT :
                    Response::HTTP_OK
            )
            ->build();

        $viewEvent->setResponse($jsonResponse);
    }

    private function support(ViewEvent $viewEvent): bool
    {
        return self::SUPPORTED_REQUEST_FORMAT === $viewEvent->getRequest()->getRequestFormat();
    }
}
