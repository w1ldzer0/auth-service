<?php

declare(strict_types=1);

namespace App\Auth\Register\Action\Api\V1;

use App\Auth\Register\Model\ResendCodeVerifyCommand;
use App\Auth\Register\Request\ResendCodeVerifyRequest;
use App\Auth\Register\Service\ResendCodeVerifyHandler;
use App\Shared\ParamConverter\RequestBodyParamConverter;
use App\Shared\Schema\Api\V1\ErrorSchema;
use App\Shared\Traits\CommandDispatchTrait;
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
    Route('/register/verify/resend', name: 'register_verify_resend', methods: [Request::METHOD_POST]),
    RequestBody(
        content: new JsonContent(
            ref: new Model(type: ResendCodeVerifyRequest::class)
        )
    ),
    Response(
        response: HttpResponse::HTTP_OK,
        description: 'Resending user registration confirmation code',
        content: new JsonContent(
            properties: [
                new Property('error', new Model(type: ErrorSchema::class)),
            ],
        )
    ),
    Tag(name: 'register')
]
class ResendCodeVerifyAction extends AbstractController
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
    public function __invoke(ResendCodeVerifyRequest $request): void
    {
        /* @see ResendCodeVerifyHandler::__invoke() */
        $this->handler(new ResendCodeVerifyCommand(new PositiveInt($request->getUserId())));
    }
}
