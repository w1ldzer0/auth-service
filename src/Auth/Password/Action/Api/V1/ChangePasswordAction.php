<?php

declare(strict_types=1);

namespace App\Auth\Password\Action\Api\V1;

use App\Auth\Password\Model\ChangePasswordCommand;
use App\Auth\Password\Request\ChangePasswordRequest;
use App\Auth\Password\Service\ChangePasswordHandler;
use App\Shared\ParamConverter\RequestBodyParamConverter;
use App\Shared\Schema\Api\V1\ErrorSchema;
use App\Shared\Traits\CommandDispatchTrait;
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
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[
    Route('/auth/password/{code}', name: 'auth_change_password', methods: [Request::METHOD_PATCH]),
    RequestBody(
        description: 'Change password', content: new JsonContent(
            ref: new Model(type: ChangePasswordRequest::class)
        )
    ),
    Response(
        response: HttpResponse::HTTP_NO_CONTENT,
        description: 'User password change',
        content: new JsonContent(
            properties: [
                new Property('error', new Model(type: ErrorSchema::class)),
            ],
        )
    ),
    Tag(name: 'auth')
]
class ChangePasswordAction extends AbstractController
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
    public function __invoke(ChangePasswordRequest $request, string $code): void
    {
        /* @see ChangePasswordHandler::__invoke() */
        $this->handler(
            new ChangePasswordCommand(
                new NotEmptyString($code),
                new NotEmptyString($request->getPassword())
            )
        );
    }
}
