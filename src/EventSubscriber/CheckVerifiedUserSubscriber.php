<?php

namespace App\EventSubscriber;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Security\AccountNotVerifiedAuthenticationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
    private RouterInterface $router;
    private TokenStorageInterface $tokenStorage;
    private UtilisateurRepository $utilisateurRepository;
    private RequestStack $requestStack;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        RouterInterface $router,
        UtilisateurRepository $utilisateurRepository,
        RequestStack $requestStack
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->router = $router;
        $this->requestStack = $requestStack;

    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginFailureEvent::class => 'onLoginFailure',
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        /** @var Utilisateur $user */
        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
        $request = $this->requestStack->getCurrentRequest();

        if ($user) {
            $user->setIpAddress($request->getClientIp());
            $this->utilisateurRepository->add($user);
        }

        if ($user && !$user->isVerified() && !in_array(
                $event->getRequest()->getPathInfo(),
                ['/verify/resend', '/verify/email']
            )) {
            $this->tokenStorage->setToken(null);

            $event->setResponse(new RedirectResponse($this->router->generate('app_verify_resend_email')));
        }

    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        if (!$event->getException() instanceof AccountNotVerifiedAuthenticationException) {
            return;
        }

        $response = new RedirectResponse(
            $this->router->generate('app_verify_resend_email')
        );
        $event->setResponse($response);
    }
}
