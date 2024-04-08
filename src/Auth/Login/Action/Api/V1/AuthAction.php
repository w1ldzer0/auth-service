<?php

declare(strict_types=1);

namespace App\Auth\Login\Action\Api\V1;

use App\Auth\Login\LoginService;
use App\Auth\Login\Request\LoginRequest;
use App\JwtToken\Command\Create\CreateJwtTokenCommand;
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
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[
    Route('/auth', name: 'auth', methods: [Request::METHOD_POST]),
    RequestBody(content: new JsonContent(
        ref: new Model(type: LoginRequest::class)
    )),
    Response(
        response: HttpResponse::HTTP_OK,
        description: 'User authentication',
        content: new JsonContent(properties: [
            new Property('payload', new Model(type: JwtTokenResponse::class)),
            new Property('error', new Model(type: ErrorSchema::class)),
        ])
    ),
    Tag(name: 'auth')
]
class AuthAction extends AbstractController
{
    use CommandDispatchTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly LoginService $loginService,
    ) {
        $this->messageBus = $messageBus;
    }

    #[ParamConverter(
        'request',
        options: ['validate' => true],
        converter: RequestBodyParamConverter::REQUEST_BODY_CONVERTER
    )]
    public function __invoke(LoginRequest $request): JwtTokenResponse
    {
        $user = $this->loginService->login($request);

        $userId = new PositiveInt($user->getId());
        /** @see CreateJwtTokenCommandHandler::__invoke() */
        $jwtToken = $this->handler(new CreateJwtTokenCommand($userId));
        Assertion::isInstanceOf($jwtToken, NotEmptyString::class);

        return new JwtTokenResponse($jwtToken->getValue());
    }
}
