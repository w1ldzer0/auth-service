<?php

declare(strict_types=1);

namespace App\Auth\Register\Action\Api\V1;

use App\Auth\Register\Model\UpdateRegisterEmailCommand;
use App\Auth\Register\Request\RegisterEmailRequest;
use App\Auth\Register\Service\UpdateRegisterEmailHandler;
use App\Shared\ParamConverter\RequestBodyParamConverter;
use App\Shared\Schema\Api\V1\ErrorSchema;
use App\Shared\Traits\CommandDispatchTrait;
use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
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
    Route('/register/email', name: 'register_email', methods: [Request::METHOD_PATCH]),
    RequestBody(content: new JsonContent(
        ref: new Model(type: RegisterEmailRequest::class)
    )),
    Response(
        response: HttpResponse::HTTP_NO_CONTENT,
        description: 'Change email registration',
        content: new JsonContent(properties: [
            new Property('error', new Model(type: ErrorSchema::class)),
        ])
    ),
    Tag(name: 'register')
]
class RegisterEmailAction extends AbstractController
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
    public function __invoke(RegisterEmailRequest $request): void
    {
        /* @see UpdateRegisterEmailHandler::__invoke() */
        $this->handler(new UpdateRegisterEmailCommand(
            new PositiveInt($request->getUserId()),
            new NotEmptyString($request->getEmail())
        ));
    }
}
