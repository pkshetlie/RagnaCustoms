<?php

namespace App\Controller;

use App\Entity\ScoreHistory;
use App\Entity\Song;
use App\Entity\SongDifficulty;
use App\Entity\SongDifficultyNotation;
use App\Form\MultipleSongDifficultyNotationType;
use App\Repository\ChangelogRepository;
use App\Service\SongDifficultyNotationService;
use App\Voter\CommunityVoteVoter;
use DateTime;
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

    #[Route('/song-difficulty/notation/info/{id}', name: 'app_song_difficulty_notation_infos')]
    public function info(Song $song, Request $request): Response
    {

        return new JsonResponse([
            "error" => false,
            "errorMessage" => false,
            "response" => $this->renderView('song_difficulty_notation/infos.html.twig'),
        ]);
    }

    #[Route('/song-difficulty/notation/detail/{id}', name: 'app_song_difficulty_notation_details')]
    public function detail(SongDifficulty $songDifficulty, Request $request): Response
    {

        $oneMonthAgo = new DateTime('-1 month');
        $monthAgo = false;
        $playedDifficultiesCount = 0;
        $playedSongsCount = 0;
        $playedDifficulty = 0;
        $remainingTime = [
            'days' => 30,
            'hours' => 0,
            'minutes' => 0,
        ];

        if ($this->getUser()) {
            if ($this->getUser()->getCreatedAt() <= $oneMonthAgo) {
                $monthAgo = true;
            } else {
                $interval = $this->getUser()->getCreatedAt()->modify('+1 month')->diff(new DateTime());
                $remainingTime = [
                    'days' => $interval->d,
                    'hours' => $interval->h,
                    'minutes' => $interval->i,
                ];
            }


            // Check if user has played this difficulty
            $playedDifficulty = $this->getUser()->getScoreHistories()->filter(
                function(ScoreHistory $sh) use ($songDifficulty) {
                    return $sh->getSongDifficulty()->getId() === $songDifficulty->getId();
                }
            )->count();

            // Check if user has played more than 5 different difficulties
            $playedDifficultiesCount = $this->getUser()->getScoreHistories()->filter(
                function(ScoreHistory $sh) use ($songDifficulty) {
                    return $sh->getSongDifficulty()->getLevel() === $songDifficulty->getLevel();
                }
            )->count();

            //on 5 different songs
            $playedSongsCount = $this->getUser()->getScoreHistories()->filter(
                function(ScoreHistory $sh) use ($songDifficulty) {
                    return $sh->getSongDifficulty()->getLevel() === $songDifficulty->getLevel();
                }
            )->toArray();

            $playedSongsCount = array_unique(array_map(function($sh) {
                return $sh->getSongDifficulty()->getSong()->getId();
            }, $playedSongsCount));

            $playedSongsCount = count($playedSongsCount);
        }

        $steps = [
            $this->isGranted('ROLE_USER'),
            $monthAgo,
            $playedDifficulty >= 1,
            $playedDifficultiesCount >= 6 && $playedSongsCount >= 6,
        ];

        return new JsonResponse([
            "error" => false,
            "errorMessage" => false,
            "response" => $this->renderView('song_difficulty_notation/details.html.twig', [
                'steps' => $steps,
                'remainingTime' => $remainingTime,
            ]),
        ]);
    }

    #[Route('/song-difficulty/notation/{id}', name: 'app_song_difficulty_notation')]
    public function index(
        Song $song,
        Request $request,
        SongDifficultyNotationService $songDifficultyNotationService
    ): Response {
        $notations = []; // Tableau d'entités SongDifficultyNotation

        foreach ($song->getSongDifficulties() as $difficulty) {
            if ($this->isGranted(CommunityVoteVoter::VOTE, $difficulty)) {
                $notations[] =
                    (new SongDifficultyNotation())
                        ->setSongDifficulty($difficulty)
                        ->setNote($songDifficultyNotationService->getUserCommunityNote($difficulty, $this->getUser()))
                        ->setUser($this->getUser());
            }
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
            foreach ($form->getData()['Notations'] as $notation) {
                $songDifficultyNotationService->setScore(
                    $notation->getSongDifficulty(),
                    $this->getUser(),
                    $notation->getNote()
                );
            }

            return new JsonResponse(['reload' => true]);
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
