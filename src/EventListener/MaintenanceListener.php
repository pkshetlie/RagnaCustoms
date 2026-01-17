<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class MaintenanceListener
{
    #[AsEventListener(event: 'kernel.request')]
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // On vérifie si la route commence par /api
        if (str_starts_with($request->getPathInfo(), '/api') || str_starts_with($request->getPathInfo(), '/rest-api')) {
            $event->setResponse(
                new JsonResponse(['message' => 'API temporairement en maintenance'], Response::HTTP_SERVICE_UNAVAILABLE)
            );
        }
    }
}
