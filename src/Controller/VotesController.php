<?php

namespace App\Controller;

use App\Entity\Song;
use App\Entity\Vote;
use App\Enum\ENotification;
use App\Form\VoteType;
use App\Repository\VoteRepository;
use App\Service\DiscordService;
use App\Service\NotificationService;
use App\Service\SongService;
use App\Service\VoteService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/song-vote', name: 'song_vote')]
class VotesController extends AbstractController
{

    #[Route(path: '/upvote/{id}', name: '_upvote')]
    public function toggleUpVote(
        VoteService $voteService,
        Song $song,
        TranslatorInterface $translator,
        NotificationService $notificationService
    ) {
        if (!$song->isAvailable()) {
            $this->addFlash('danger', $translator->trans("Song not available for vote"));
        } elseif (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('danger', $translator->trans("Login to vote"));
        } elseif (!$voteService->canUpDownVote($song, $this->getUser())) {
            $this->addFlash('danger', $translator->trans("Play the song first"));
        } else {
            $lastVote = $voteService->getLast($song, $this->getUser());
            $now = new DateTime();
            $interval = $lastVote?->getUpdatedAt()->diff($now);

            $voteService->toggleUpVote($song, $this->getUser());

            if (!$interval || $interval->i > 10 || $interval->h !== 0) {
                foreach ($song->getMappers() as $mapper) {
                    if ($mapper->hasNotificationPreference(ENotification::Mapper_new_feedback)) {
                        $userSongVoteCounter = $song->isVoteCounterBy($this->getUser());

                        if (!$userSongVoteCounter) {
                            break;
                        }

                        if ($userSongVoteCounter->getVotesIndc() === null) {
                            break;
                        }

                        $message = 'You got '.($userSongVoteCounter->getVotesIndc() ? 'an Up-' : 'a Down-').'vote on <a href="'.$this->generateUrl('song_detail',['slug' => $song->getSlug()]).'">'.htmlentities($song->getName())."</a>";
                            $notificationService->send(
                                $mapper,
                                $message
                            );
                        }

                }
            }
        }

        return new JsonResponse(
            ['result' => $this->renderView('songs/partial/downupvote.html.twig', ['song' => $song,])]
        );
    }

    #[Route(path: '/downvote/{id}', name: '_downvote')]
    public function toggleDownVote(
        VoteService $voteService,
        Song $song,
        TranslatorInterface $translator,
        NotificationService $notificationService
    ) {
        if (!$song->isAvailable()) {
            $this->addFlash('danger', $translator->trans("Song not available for vote"));
        } elseif (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('danger', $translator->trans("Login to vote"));
        } elseif (!$voteService->canUpDownVote($song, $this->getUser())) {
            $this->addFlash('danger', $translator->trans("Play the song first"));
        } else {
            $lastVote = $voteService->getLast($song, $this->getUser());
            $now = new DateTime();
            $interval = $lastVote?->getUpdatedAt()->diff($now);

            $voteService->toggleDownVote($song, $this->getUser());

            if (!$interval || $interval->i > 10 || $interval->h !== 0) {
                foreach ($song->getMappers() as $mapper) {
                    if ($mapper->hasNotificationPreference(ENotification::Mapper_new_feedback)) {
                        $userSongVoteCounter = $song->isVoteCounterBy($this->getUser());

                        if (!$userSongVoteCounter) {
                            break;
                        }

                        if ($userSongVoteCounter->getVotesIndc() === null) {
                            break;
                        }

                        $notificationService->send(
                            $mapper,
                            'You got '.($userSongVoteCounter->getVotesIndc(
                            ) ? 'an Up-' : 'a Down-').'vote on <a href="'.$this->generateUrl(
                                'song_detail',
                                ['slug' => $song->getSlug()]
                            ).'">'.htmlentities($song->getName())."</a>"
                        );
                    }
                }
            }
        }

        return new JsonResponse(['result' => $this->renderView('songs/partial/downupvote.html.twig', ['song' => $song])]
        );
    }

    #[Route(path: '/dismiss/{id}', name: '_dismiss')]
    public function dismissVote(
        VoteService $voteService,
        SongService $songService,
        Song $song,
        TranslatorInterface $translator
    ): JsonResponse {
        if (!$song->isAvailable()) {
            $this->addFlash('danger', $translator->trans("Song not available for vote"));
        } elseif (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('danger', $translator->trans("Login to vote"));
        } elseif (!$voteService->canUpDownVote($song, $this->getUser())) {
            $this->addFlash('danger', $translator->trans("Play the song first"));
        } else {
            $voteService->dismissVote($song, $this->getUser());
        }

        return new JsonResponse([
            'result' => $this->renderView(
                'songs/partial/vote_it_box.html.twig',
                ['songs' => $songService->getLastPlayedToVote($this->getUser())]
            ),
        ]);
    }

    /**
     * @param Request $request
     * @param Song $song
     * @param VoteRepository $voteRepository
     * @param TranslatorInterface $translator
     *
     * @return Response
     */
    #[Route(path: '/review/{id}', name: '_review')]
    public function songReview(
        Request $request,
        Song $song,
        ManagerRegistry $doctrine,
        VoteRepository $voteRepository,
        TranslatorInterface $translator,
        VoteService $voteService,
        NotificationService $notificationService,
        DiscordService $discordService
    ): Response {
        if ($song == null) {
            return new JsonResponse([
                "error" => true,
                "errorMessage" => $translator->trans("You need an account!"),
                "response" => $translator->trans("Custom song not found!"),
            ]);
        }

        if (!$this->isGranted('ROLE_USER')) {
            return new JsonResponse([
                "error" => true,
                "errorMessage" => $translator->trans("You need an account!"),
                "response" => $this->renderView('songs/partial/detail_vote.html.twig', [
                    "song" => $song,
                    'message' => $translator->trans("You need an account!"),
                ]),
            ]);
        }

        if (!$voteService->canReview($song, $this->getUser())) {
            return new JsonResponse([
                "error" => true,
                "errorMessage" => $translator->trans("You need to play the song first!"),
                "response" => $translator->trans("Custom song not found!"),
            ]);
        }

        if ($song->getWip()) {
            return new JsonResponse([
                "error" => true,
                "errorMessage" => $translator->trans("You can't review a Work in progres SONG"),
                "response" => $translator->trans("Work in progress! You can't review that."),

            ]);
        }

        if ($song->getMappers()->contains($this->getUser())) {
            return new JsonResponse([
                "error" => true,
                "errorMessage" => $translator->trans("You need an account!"),
                "response" => $this->renderView('songs/partial/detail_vote.html.twig', [
                    "song" => $song,
                    'message' => $translator->trans("You can't review a custom song you've submitted"),
                ]),
            ]);
        }

        $vote = $voteRepository->findOneBy([
            'song' => $song,
            'user' => $this->getUser(),
        ]);

        if ($vote == null) {
            $vote = new Vote();
            $vote->setUser($this->getUser());
            $vote->setSong($song);
        }
        $voteBefore = clone $vote;
        $form = $this->createForm(VoteType::class, $vote, [
            'method' => "post",
            'action' => $this->generateUrl('song_vote_review', ['id' => $song->getId()]),
            "attr" => [
                "class" => "form ajax-form",
                "data-url" => $this->generateUrl("song_vote_review", ['id' => $song->getId()]),
            ],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            if ($vote->getId() != null) {
                $voteService->subScore($song, $voteBefore);
            }
            if ($this->isGranted('ROLE_USER') && $this->getUser()->isCertified()) {
                $vote->setIsModerated(true);
            }
            $vote->setLevelQuality(null);
            $vote->setFlow(null);
            $voteService->addScore($song, $vote);

            if ($vote->getFeedback() != null && !empty($vote->getFeedback()) && $vote->getFeedback(
                ) !== $voteBefore->getFeedback()) {
                $discordService->sendFeedback($vote);
                foreach ($song->getMappers() as $mapper) {
                    if ($mapper->hasNotificationPreference(ENotification::Mapper_new_feedback)) {
                        $notificationService->send(
                            $mapper,
                            'You got a review on <a href="'.$this->generateUrl(
                                'song_detail',
                                ['slug' => $song->getSlug()]
                            ).'">'.htmlentities($song->getName())."</a>"
                        );
                    }
                }

                $vote->setIsModerated(false);
            }
            $em->persist($vote);
            $em->flush();

            return new JsonResponse([
                "error" => false,
                "errorMessage" => false,
                "response" => $this->renderView("songs/partial/vote_small.html.twig", [
                    'song' => $song,
                    "vote" => $vote,
                ]),
            ]);
        }

        return new JsonResponse([
            "error" => false,
            "errorMessage" => false,
            "response" => $this->renderView("songs/partial/form_review.html.twig", [
                'song' => $song,
                'form' => $form->createView(),
            ]),
        ]);
    }
}
