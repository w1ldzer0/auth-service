<?php

declare(strict_types=1);

namespace App\Auth\Password\Action\Api\V1;

use App\Auth\Password\Model\RecoveryPasswordCommand;
use App\Auth\Password\Request\RecoveryPasswordRequest;
use App\Auth\Password\Service\RecoveryPasswordHandler;
use App\Shared\ParamConverter\RequestBodyParamConverter;
use App\Shared\Schema\Api\V1\ErrorSchema;
use App\Shared\Traits\CommandDispatchTrait;
use App\Shared\ValueObject\Email;
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
    Route('/auth/password/recovery', name: 'auth_password_recovery', methods: [Request::METHOD_POST]),
    RequestBody(
        description: 'Password recovery',
        content: new JsonContent(
            ref: new Model(type: RecoveryPasswordRequest::class)
        )
    ),
    Response(
        response: HttpResponse::HTTP_NO_CONTENT,
        description: 'Submit a password change request',
        content: new JsonContent(
            properties: [
                new Property('error', new Model(type: ErrorSchema::class)),
            ],
        )
    ),
    Tag(name: 'auth')
]
class RecoveryPasswordAction extends AbstractController
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
    public function __invoke(RecoveryPasswordRequest $request): void
    {
        $email = new Email($request->getEmail());

        /* @see RecoveryPasswordHandler::__invoke() */
        $this->handler(new RecoveryPasswordCommand($email));
    }
}
