<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ApiRequestListener
{
    private $logger;
    private $requestStack;


    public function __construct(LoggerInterface $apiLogger, RequestStack $requestStack)
    {
        $this->logger = $apiLogger;
        $this->requestStack = $requestStack;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            $this->logger->info('API request', [
                'date' => date('Y-m-d H:i:s'),
                'method' => $request->getMethod(),
                'path' => $request->getPathInfo(),
                'ip' => $request->getClientIp(),
                'user_agent' => $request->headers->get('User-Agent'),
            ]);
        }
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        // $data = $event->getData();
        /** @var User $user */
        $user = $event->getUser();

        $this->logger->info('API login success', [
            'date' => date('Y-m-d H:i:s'),
            'username' => $user->getFullName(),
            'email' => $user->getEmail(),
            'ip' => $this->requestStack->getCurrentRequest()->getClientIp(),
            'user_agent' => $event->getResponse()->headers->get('User-Agent'),
        ]);
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $exception = $event->getException();
        $requestData = json_decode($event->getRequest()->getContent(), true);
        $username = $requestData['username'] ?? null;
        $this->logger->warning('API login failure', [
            'date' => date('Y-m-d H:i:s'),
            'message' => $exception->getMessage(),
            'email' => $username,
            'ip' => $this->requestStack->getCurrentRequest()->getClientIp(),
            'user_agent' => $event->getRequest()->headers->get('User-Agent'),
        ]);
    }

    // Vous pouvez ajouter d'autres méthodes pour gérer les erreurs ou d'autres niveaux de logs
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $this->logger->error('API error', [
            'date' => date('Y-m-d H:i:s'),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}