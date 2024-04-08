<?php

declare(strict_types=1);

namespace App\Auth\Oauth\Google\Action\Api\V1;

use App\Auth\Oauth\Google\Command\Authorization\AuthorizationCommand;
use App\Auth\Oauth\Google\Command\Authorization\AuthorizationCommandHandler;
use App\Auth\Oauth\Google\Request\GoogleOauthRequest;
use App\Auth\Shared\Exception\AuthorizationFailedException;
use App\JwtToken\Command\Create\CreateJwtTokenCommand;
use App\JwtToken\Command\Create\CreateJwtTokenCommandHandler;
use App\JwtToken\Response\JwtTokenResponse;
use App\Shared\Exception\RequestValidateException;
use App\Shared\ParamConverter\RequestBodyParamConverter;
use App\Shared\Response\ErrorCode;
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

#[Route('/google', name: 'google', methods: [Request::METHOD_POST])]
#[RequestBody(content: new JsonContent(ref: new Model(type: GoogleOauthRequest::class)))]
#[Response(
    response: HttpResponse::HTTP_OK,
    description: 'User Google oauth authentication',
    content: new JsonContent(properties: [
        new Property('payload', new Model(type: JwtTokenResponse::class)),
        new Property('error', new Model(type: ErrorSchema::class)),
    ])
)]
#[Tag(name: 'oauth')]
class OauthAction extends AbstractController
{
    use CommandDispatchTrait;

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    #[ParamConverter(
        'request',
        options: ['validate' => true],
        converter: RequestBodyParamConverter::REQUEST_BODY_CONVERTER
    )]
    public function __invoke(GoogleOauthRequest $request): JwtTokenResponse
    {
        $code = new NotEmptyString($request->getCode());

        try {
            /** @see AuthorizationCommandHandler::__invoke() */
            $userId = $this->handler(new AuthorizationCommand($code));
            Assertion::isInstanceOf($userId, PositiveInt::class);
        } catch (AuthorizationFailedException $exception) {
            throw new RequestValidateException(
                ErrorCode::NOT_VALIDATE_GOOGLE_OAUTH_CODE,
                ['code' => 'Not validate code']
            );
        }

        /** @see CreateJwtTokenCommandHandler::__invoke() */
        $jwtToken = $this->handler(new CreateJwtTokenCommand($userId));
        Assertion::isInstanceOf($jwtToken, NotEmptyString::class);

        return new JwtTokenResponse($jwtToken->getValue());
    }
}
