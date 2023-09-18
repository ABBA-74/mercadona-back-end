<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserTraceabilitySubscriber implements EventSubscriberInterface
{
    
    public function __construct(private TokenStorageInterface $tokenStorage)
    { }

    public function onUserView(ViewEvent $event): void
    {
        // Get the entity being used
        $entity = $event->getControllerResult();

        // Get the HTTP method being used
        $method = $event->getRequest()->getMethod();

        // Retrieve the current user
        $user = $this->tokenStorage->getToken()->getUser();

        // Check if the entity is an instance of Image or User,
        // or if the HTTP method is not one of POST, PATCH, or PUT
        if (
            $entity instanceof Image
            || $entity instanceof User
            || !in_array($method, [Request::METHOD_POST, Request::METHOD_PATCH,     Request::METHOD_PUT])
        ) {
            return;
        }

        // Check if the entity has a setUser() method before calling it
        if (method_exists($entity, 'setUser')) {
            $entity->setUser($user);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onUserView', EventPriorities::PRE_WRITE],
        ];
    }
}
