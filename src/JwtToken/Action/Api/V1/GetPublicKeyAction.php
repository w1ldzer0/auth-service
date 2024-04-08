<?php

namespace App\JwtToken\Action\Api\V1;

use App\JwtToken\Repository\PublicKeyRepositoryInterface;
use App\JwtToken\Response\PublicKeyResponse;
use App\Shared\Schema\Api\V1\ErrorSchema;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Routing\Annotation\Route;

#[
    Route('/jwt-token/public-key', name: 'jwt_token_public_key', methods: [Request::METHOD_GET]),
    Response(
        response: HttpResponse::HTTP_OK,
        description: 'Return public key for jwt-token',
        content: new JsonContent(properties: [
            new Property('payload', new Model(type: PublicKeyResponse::class)),
            new Property('error', new Model(type: ErrorSchema::class)),
        ])
    ),
    Tag(name: 'jwt-token')
]
class GetPublicKeyAction extends AbstractController
{
    public function __construct(
        private readonly PublicKeyRepositoryInterface $publicKeyRepository,
    ) {
    }

    public function __invoke(): PublicKeyResponse
    {
        $publicKey = $this->publicKeyRepository->get();

        return new PublicKeyResponse($publicKey->getValue());
    }
}
