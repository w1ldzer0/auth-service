<?php

declare(strict_types=1);

namespace App\JwtToken\Listener;

use App\JwtToken\Model\JwtUser;
use App\JwtToken\Payload;
use Assert\Assertion;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener('lexik_jwt_authentication.on_jwt_created')]
class AddUserIdJWTCreatedListener
{
    public function __invoke(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();
        $user = $this->getUser($event);
        $payload[Payload::USER_ID] = $user->getId()->getValue();

        $event->setData($payload);
    }

    private function getUser(JWTCreatedEvent $event): JwtUser
    {
        Assertion::isInstanceOf($event->getUser(), JwtUser::class);

        /** @var JwtUser $user */
        $user = $event->getUser();

        return $user;
    }
}
