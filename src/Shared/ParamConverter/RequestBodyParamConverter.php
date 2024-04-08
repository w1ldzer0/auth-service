<?php

declare(strict_types=1);

namespace App\Shared\ParamConverter;

use App\Shared\Exception\RequestValidateException;
use App\Shared\RequestViolationListConverter;
use App\Shared\Response\ErrorCode;
use Assert\Assertion;
use Assert\AssertionFailedException;
use FOS\RestBundle\Request\RequestBodyParamConverter as FosRequestBodyParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class RequestBodyParamConverter implements ParamConverterInterface
{
    public const REQUEST_BODY_CONVERTER = 'request_body_converter';
    public const VALIDATION_ERRORS_ARGUMENT = 'validationErrors';

    public function __construct(
        private readonly RequestViolationListConverter $converter,
        private readonly FosRequestBodyParamConverter $requestBodyParamConverter,
        private readonly string $validationErrorsArgument = self::VALIDATION_ERRORS_ARGUMENT
    ) {
    }

    /**
     * @throws AssertionFailedException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $result = $this->requestBodyParamConverter->apply($request, $configuration);
        $this->checkIsValidRequest($request);

        return $result;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return
            $configuration->getClass() !== null &&
            $configuration->getConverter() === self::REQUEST_BODY_CONVERTER;
    }

    /**
     * @throws AssertionFailedException
     */
    private function checkIsValidRequest(Request $request): void
    {
        $validationErrors = $request->attributes->get($this->validationErrorsArgument);

        if ($validationErrors === null) {
            return;
        }

        Assertion::isInstanceOf($validationErrors, ConstraintViolationListInterface::class);

        if (count($validationErrors) <= 0) {
            return;
        }

        throw new RequestValidateException(
            ErrorCode::REQUEST_INVALIDATE,
            $this->converter->convertToArray($validationErrors),
        );
    }
}
