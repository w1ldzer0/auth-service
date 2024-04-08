<?php

declare(strict_types=1);

namespace App\Auth\Register\Action\Api\V1;

use App\Auth\Register\Request\RegisterRequest;
use App\Auth\Register\Service\RegisterRequestHandler;
use App\JwtToken\Command\Create\CreateJwtTokenCommand;
use App\JwtToken\JwtTokenService;
use App\JwtToken\Response\JwtTokenResponse;
use App\Shared\ParamConverter\RequestBodyParamConverter;
use App\Shared\Schema\Api\V1\ErrorSchema;
use App\Shared\Traits\CommandDispatchTrait;
use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
use Assert\Assertion;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[
    Route('/register', name: 'register', methods: [Request::METHOD_POST]),
    RequestBody(content: new JsonContent(
        ref: new Model(type: RegisterRequest::class)
    )),
    Response(
        response: HttpResponse::HTTP_OK,
        description: 'User registration',
        content: new JsonContent(properties: [
            new Property('payload', new Model(type: JwtTokenResponse::class)),
            new Property('error', new Model(type: ErrorSchema::class)),
        ])
    ),
    Tag(name: 'register')
]
class RegisterAction extends AbstractController
{
    use CommandDispatchTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly JwtTokenService $jwtTokenService,
        private readonly RequestStack $requestStack
    ) {
        $this->messageBus = $messageBus;
    }

    #[ParamConverter(
        'request',
        options: ['validate' => true],
        converter: RequestBodyParamConverter::REQUEST_BODY_CONVERTER
    )]
    public function __invoke(RegisterRequest $request): JwtTokenResponse
    {
        /* @see RegisterRequestHandler::__invoke() */
        $registerUserId = $this->handler($request);

        Assertion::notNull($registerUserId);
        Assertion::isInstanceOf($registerUserId, PositiveInt::class);

        /** @see CreateJwtTokenCommandHandler::__invoke() */
        $jwtToken = $this->handler(new CreateJwtTokenCommand($registerUserId));
        Assertion::isInstanceOf($jwtToken, NotEmptyString::class);

        return new JwtTokenResponse($jwtToken->getValue());
    }
}
