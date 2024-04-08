<?php

declare(strict_types=1);

namespace App\Auth\Register\Action\Api\V1;

use App\Auth\Register\Request\RegisterVerifyRequest;
use App\Auth\Register\Service\RegisterVerifyService;
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
    Route('/register/verify', name: 'register_verify', methods: [Request::METHOD_POST]),
    RequestBody(
        content: new JsonContent(
            ref: new Model(type: RegisterVerifyRequest::class)
        )
    ),
    Response(
        response: HttpResponse::HTTP_OK,
        description: 'User registration confirmation',
        content: new JsonContent(
            properties: [
                new Property(property: 'payload', ref: new Model(type: JwtTokenResponse::class)),
                new Property('error', new Model(type: ErrorSchema::class)),
            ],
        )
    ),
    Tag(name: 'register')
]
class RegisterVerifyAction extends AbstractController
{
    use CommandDispatchTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private readonly RegisterVerifyService $registerVerifyService,
    ) {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws \App\Shared\Exception\NotFoundException
     */
    #[ParamConverter(
        'request',
        options: ['validate' => true],
        converter: RequestBodyParamConverter::REQUEST_BODY_CONVERTER
    )]
    public function __invoke(RegisterVerifyRequest $request): JwtTokenResponse
    {
        $user = $this->registerVerifyService->verify($request);

        $userId = new PositiveInt($user->getId());
        /** @see CreateJwtTokenCommandHandler::__invoke() */
        $jwtToken = $this->handler(new CreateJwtTokenCommand($userId));
        Assertion::isInstanceOf($jwtToken, NotEmptyString::class);

        return new JwtTokenResponse($jwtToken->getValue());
    }
}
