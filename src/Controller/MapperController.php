<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\MapperRepository;
use App\Repository\UtilisateurRepository;
use Pkshetlie\PaginationBundle\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class MapperController extends AbstractController
{

    #[Route(path: '/mappers', name: 'mappers')]
    public function list(Request $request, MapperRepository $mappersRepository, PaginationService $paginationService): Response
    {
        $qb = $mappersRepository->createQueryBuilder('m')->orderBy('m.mapCount', 'desc');

        $mappers = $paginationService->setDefaults(50)->process($qb, $request->query);

        return $this->render('mapper/index.html.twig', [
            'mappers' => $mappers,
        ]);
    }

    #[Route(path: '/application', name: 'application')]
    public function index(): Response
    {
        return $this->render('application/index.html.twig', [
            'controller_name' => 'ApplicationController',
        ]);
    }

    #[Route(path: '/locale/{locale}', name: 'change_locale')]
    public function changeLocale(Request $request, string $locale, SessionInterface $session)
    {
        $session->set('_locale', $locale);

        if ($request->headers->get('referer') == null) {
            return $this->redirectToRoute('home');
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
