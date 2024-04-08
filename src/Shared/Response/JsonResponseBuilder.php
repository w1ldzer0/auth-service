<?php

declare(strict_types=1);

namespace App\Shared\Response;

use App\Shared\Response\ResponseField\FieldInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonResponseBuilder
{
    private const JSON_FORMAT = 'json';
    private const PATH_SEPARATOR = '.';

    private array $response = [];
    private int $httpStatusCode = HttpResponse::HTTP_OK;
    private array $headers = [];

    private readonly Serializer $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(
            null, new CamelCaseToSnakeCaseNameConverter()
        )];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public static function create(): JsonResponseBuilder
    {
        return new JsonResponseBuilder();
    }

    public function build(): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($this->response, self::JSON_FORMAT),
            $this->httpStatusCode,
            $this->headers,
            true
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addField(FieldInterface $responseField): static
    {
        $this->response = $this->addNestedElement($this->response, $responseField);

        return $this;
    }

    /**
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArrayOffset
     */
    private function addNestedElement(array $array, FieldInterface $responseField): array
    {
        $result = $array;
        $keys = explode(self::PATH_SEPARATOR, $responseField->getPath());

        $tmp = &$result;

        while (count($keys) > 0) {
            $key = array_shift($keys);
            if (!is_array($tmp)) {
                $tmp = [];
            }
            $tmp = &$tmp[$key];
        }

        $tmp = $responseField->getValue();

        return $result;
    }

    public function setHttpStatusCode(int $httpStatusCode): static
    {
        $this->httpStatusCode = $httpStatusCode;

        return $this;
    }

    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }
}
