<?php

namespace App\Controller;

use App\Entity\Song;
use App\Entity\SongDifficultyNotation;
use App\Form\MultipleSongDifficultyNotationType;
use App\Repository\ChangelogRepository;
use App\Service\SongDifficultyNotationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SongDifficultyNotationController extends AbstractController
{
    public function __construct(private readonly ChangelogRepository $changelogRepository)
    {
    }

    #[Route('/song-difficulty/notation/{id}', name: 'app_song_difficulty_notation')]
    public function index(Song $song,Request $request, SongDifficultyNotationService $songDifficultyNotationService): Response
    {
        $notations = []; // Tableau d'entités SongDifficultyNotation

        // Créer les entités nécessaires pour chaque notation permise
        foreach ($songDifficultyNotationService->getAllowedNotation($song, $this->getUser()) as $notation) {
            $notations[] =
                (new SongDifficultyNotation())
                ->setSongDifficulty($notation['difficulty'])
                ->setNote($notation['note'])
                ->setUser($this->getUser());
        }

        // Créer le formulaire avec les entités directement
        $form = $this->createForm(MultipleSongDifficultyNotationType::class, ['Notations' => $notations], [
            'action' => $this->generateUrl('app_song_difficulty_notation', ['id' => $song->getId()]),
            "attr" => [
                "class" => "form ajax-form",
                "data-url" => $this->generateUrl("app_song_difficulty_notation", ['id' => $song->getId()]),
            ],
        ]);

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            foreach($form->getData()['Notations'] as $notation){
                $songDifficultyNotationService->setScore($notation->getSongDifficulty(), $this->getUser(),$notation->getNote());
            }

            return new JsonResponse(['reload'=>true]);
        }

        return new JsonResponse([
            "error" => false,
            "errorMessage" => false,
            "response" => $this->renderView('song_difficulty_notation/index.html.twig', [
                'form' => $form->createView(),
            ]),
        ]);
    }
}
