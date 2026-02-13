<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CmsController extends AbstractController
{
    #[Route(path: '/getting-started', name: 'getting_started')]
    public function gettingStarted(): Response
    {
        return $this->render('cms/getting_started.html.twig');
    }

    #[Route(path: '/ranking-system', name: 'ranking_system')]
    public function rankingSystem(): Response
    {
        return $this->render('cms/ranking_system.html.twig');
    }

    #[Route(path: '/acceptance-criteria', name: 'acceptance_criteria')]
    public function acceptanceCriteria(): Response
    {
        return $this->render('cms/acceptance_criteria.html.twig');
    }

    #[Route(path: '/premium', name: 'premium')]
    public function premium(): Response
    {
        return $this->render('cms/premium.html.twig');
    }

    #[Route(path: '/constant-companions-event', name: 'constant_companions_event')]
    public function constantCompanionsEvent(): Response
    {
        return $this->render('cms/constant_companions_event.html.twig');
    }

    #[Route(path: '/', name: 'home')]
    public function homepage(): Response
    {
        $patreonImages = [
            '/apps/01.webp',
            '/apps/02.webp',
            '/apps/03.webp',
            '/apps/04.webp',
        ];
        $randomKey = array_rand($patreonImages);

        return $this->render('cms/homepage.html.twig', [
            'patreonImage' => $patreonImages[$randomKey],
        ]);
    }

}
