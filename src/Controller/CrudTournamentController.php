<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account/tournament')]
final class CrudTournamentController extends AbstractController
{
    #[Route(name: 'app_crud_tournament_index', methods: ['GET'])]
    public function index(TournamentRepository $tournamentRepository): Response
    {
        if (!$this->isGranted('ROLE_PREMIUM_LVL3')) {
            $this->addFlash(
                'info',
                'The custom tournament feature is only available <br>for <b>"Premium account Tier 3"</b>'
            );

            return $this->redirectToRoute('premium');
        }


        return $this->render('crud_tournament/index.html.twig', [
            'tournaments' => $tournamentRepository->findBy(['owner' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_crud_tournament_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tournament = new Tournament();

        return $this->edit($request, $tournament, $entityManager);
    }

    #[Route('/{id}/edit', name: 'app_crud_tournament_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tournament $tournament, EntityManagerInterface $entityManager): Response
    {
        if ($tournament->getId() && $tournament->getOwner() !== $this->getUser()) {
            return $this->redirectToRoute('app_crud_tournament_index');
        }

        $tournament->setOwner($this->getUser());
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_tournament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_tournament/edit.html.twig', [
            'tournament' => $tournament,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_tournament_show', methods: ['GET'])]
    public function show(Tournament $tournament): Response
    {
        return $this->render('crud_tournament/show.html.twig', [
            'tournament' => $tournament,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_tournament_delete', methods: ['POST'])]
    public function delete(Request $request, Tournament $tournament, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournament->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tournament);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crud_tournament_index', [], Response::HTTP_SEE_OTHER);
    }
}
