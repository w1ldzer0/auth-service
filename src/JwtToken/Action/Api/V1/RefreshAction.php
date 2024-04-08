<?php

namespace App\JwtToken\Action\Api\V1;

use App\JwtToken\JwtTokenService;
use App\JwtToken\Request\TokenRefreshRequest;
use App\JwtToken\Response\JwtTokenResponse;
use App\Shared\Exception\NotValidateCodeException;
use App\Shared\ParamConverter\RequestBodyParamConverter;
use App\Shared\Schema\Api\V1\ErrorSchema;
use App\Shared\ValueObject\NotEmptyString;
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
use Symfony\Component\Routing\Annotation\Route;

#[
    Route('/jwt-token/refresh', name: 'jwt_token_refresh', methods: [Request::METHOD_POST]),
    RequestBody(content: new JsonContent(
        ref: new Model(type: TokenRefreshRequest::class)
    )),
    Response(
        response: HttpResponse::HTTP_OK,
        description: 'Re-generate jwt-token',
        content: new JsonContent(properties: [
            new Property('payload', new Model(type: JwtTokenResponse::class)),
            new Property('error', new Model(type: ErrorSchema::class)),
        ])
    ),
    Tag(name: 'jwt-token')
]
class RefreshAction extends AbstractController
{
    public function __construct(
        private readonly JwtTokenService $jwtTokenService
    ) {
    }

    /**
     * @throws NotValidateCodeException
     */
    #[ParamConverter(
        'request',
        options: ['validate' => true],
        converter: RequestBodyParamConverter::REQUEST_BODY_CONVERTER
    )]
    public function __invoke(TokenRefreshRequest $request): JwtTokenResponse
    {
        $jwtToken = $this->jwtTokenService->refresh(
            new NotEmptyString($request->getJwtToken())
        );

        return new JwtTokenResponse($jwtToken->getValue());
    }
}
