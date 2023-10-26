<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessHandler implements EventSubscriberInterface
{
    private $tokenLifetime;

    public function __construct(int $tokenLifetime)
    {
        $this->tokenLifetime = $tokenLifetime;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => ['onAuthenticationSuccess', 100],
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $event->getResponse()->headers->setCookie(
            new Cookie(
                'BEARER', // Cookie name, should be the same as in config/packages/lexik_jwt_authentication.yaml.
                $event->getData()['token'], // cookie value
                time() + $this->tokenLifetime, // expiration
                '/', // path
                null, // domain, null means that Symfony will generate it on its own.
                true, // secure
                true, // httpOnly
                false, // raw
                'lax' // same-site parameter, can be 'lax' or 'strict'.
            )
        );

        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        // Add data to the response if authentication successeded
        /**  @var User $user */
        $data['fullname'] = $user->getFullName();
        $data['email'] = $user->getEmail();
        $data['roles'] = $user->getRoles();
        $data['exp'] = time() + $this->tokenLifetime;

        $event->setData($data);
    }
}
