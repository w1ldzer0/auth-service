<?php

declare(strict_types=1);

namespace App\JwtToken\Command\Create;

use App\JwtToken\Model\JwtUser;
use App\Shared\ValueObject\NotEmptyString;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

#[AsMessageHandler]
class CreateJwtTokenCommandHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager,
    ) {
    }

    public function __invoke(CreateJwtTokenCommand $command): NotEmptyString
    {
        $user = new JwtUser($command->getUserId());

        return new NotEmptyString($this->jwtManager->create($user));
    }
}
