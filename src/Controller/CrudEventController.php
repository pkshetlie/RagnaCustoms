<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account/event')]
final class CrudEventController extends AbstractController
{
    #[Route(name: 'app_crud_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        if (!$this->isGranted('ROLE_PREMIUM_LVL3')) {
            $this->addFlash(
                'info',
                'The custom event feature is only available <br>for <b>"Premium account Tier 3"</b>'
            );

            return $this->redirectToRoute('premium');
        }


        return $this->render('crud_event/index.html.twig', [
            'events' => $eventRepository->findBy(['owner' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_crud_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('crud_event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crud_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crud_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
