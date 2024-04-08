<?php

declare(strict_types=1);

namespace App\Client\ApiClient;

use App\Client\ApiClient\Request\AbstractRequest;
use App\Shared\Exception\ExternalServerException;
use App\Shared\Exception\NotFoundException;
use App\Shared\Exception\UniqueConstraintViolationException;
use App\Shared\Response\ErrorCode;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiClient
{
    private HttpClientInterface $client;
    protected const PAYLOAD_FIELD = 'payload';

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws ExternalServerException
     * @throws UniqueConstraintViolationException
     * @throws NotFoundException
     */
    public function sendRequest(AbstractRequest $request): array
    {
        try {
            $response = $this->client->request(
                $request->getMethod(),
                $request->getUri(),
                [
                    'headers' => $request->getHeader(),
                    'query' => $request->getQuery(),
                    'json' => $request->getRequestBody(),
                ]
            );
        } catch (TransportExceptionInterface $e) {
            throw new ExternalServerException(
                'Failed to send request: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        return $this->responseHandler($response);
    }

    /**
     * @throws AssertionFailedException
     * @throws ExternalServerException
     * @throws NotFoundException
     * @throws UniqueConstraintViolationException
     */
    protected function responseHandler(ResponseInterface $response): array
    {
        try {
            $httpStatusCode = $response->getStatusCode();
            $bodyResponse = $response->toArray();
        } catch (HttpExceptionInterface $e) {
            throw $this->clientExceptionHandler($e);
        } catch (DecodingExceptionInterface|TransportExceptionInterface $e) {
            throw new ExternalServerException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        if ($httpStatusCode === Response::HTTP_NO_CONTENT) {
            return [];
        }

        return $this->getPayload($bodyResponse);
    }

    protected function clientExceptionHandler(
        HttpExceptionInterface $exception,
    ): ExternalServerException|UniqueConstraintViolationException|NotFoundException {
        return match ($exception->getCode()) {
            Response::HTTP_NOT_FOUND => new NotFoundException(
                $exception->getMessage(), $exception->getCode(), $exception
            ),
            Response::HTTP_CONFLICT => new UniqueConstraintViolationException(
                ErrorCode::USER_UNIQUE_KEY_EXIST,
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            ),
            default => new ExternalServerException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            ),
        };
    }

    private function getPayload(array $bodyResponse): array
    {
        Assertion::keyExists($bodyResponse, self::PAYLOAD_FIELD);

        $payload = $bodyResponse[self::PAYLOAD_FIELD];

        Assertion::isArray($payload);

        return $payload;
    }
}
