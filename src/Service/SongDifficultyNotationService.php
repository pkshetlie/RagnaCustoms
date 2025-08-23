<?php


namespace App\Service;


use App\Entity\Song;
use App\Entity\SongDifficulty;
use App\Entity\SongDifficultyNotation;
use App\Entity\Utilisateur;
use App\Repository\ScoreHistoryRepository;
use App\Repository\SongDifficultyNotationRepository;

class SongDifficultyNotationService
{
    public function __construct(
        private readonly SongDifficultyNotationRepository $songDifficultyNotationRepository,
        private readonly ScoreHistoryRepository $scoreHistoryRepository,
    ) {
    }

    public function setScore(SongDifficulty $songDifficulty, Utilisateur $utilisateur, int $note)
    {
        if ($this->isAllowedToNote($songDifficulty, $utilisateur) === false) {
            return false;
        }

        $notation = $this->songDifficultyNotationRepository->findOneBy([
            'songDifficulty' => $songDifficulty,
            'user' => $utilisateur,
        ]);

        if ($notation === null) {
            $notation = new SongDifficultyNotation();
            $notation->setSongDifficulty($songDifficulty);
            $notation->setUser($utilisateur);
        }

        $notation->setNote($note);
        $this->songDifficultyNotationRepository->add($notation);

        return true;
    }

    public function isAllowedToNote(SongDifficulty $songDifficulty, Utilisateur $utilisateur): bool
    {
        $scoreHistory = $this->scoreHistoryRepository->findOneBy([
            'songDifficulty' => $songDifficulty,
            'user' => $utilisateur,
        ]);

        if ($scoreHistory === null) {
            return true;
        }

        return false;
    }

    public function getCommunityNotations(Song $song): array
    {
        $difficultyNotations = [];

        foreach ($song->getSongDifficulties() as $difficulty) {
            $notation = $this
                ->songDifficultyNotationRepository
                ->createQueryBuilder('sdn')
                ->select('SUM(sdn.note) AS total')
                ->addSelect('COUNT(sdn.id) AS count')
                ->where('sdn.songDifficulty = :difficulty')
                ->setParameter('difficulty', $difficulty)
                ->getQuery()->getOneOrNullResult();

            if (!$notation || $notation['count'] < 1) {
                $difficultyNotations[$difficulty->getDifficultyRank()->getLevel()] = [
                    'difficulty' => $difficulty,
                    'level' => $difficulty->getDifficultyRank()->getLevel()
                ];
                continue;
            }

            $difficultyNotations[$difficulty->getDifficultyRank()->getLevel()] =
                [
                    'difficulty' => $difficulty,
                    'level' => $notation['total'] / $notation['count'],
                ];
        }

        return $difficultyNotations;
    }

}
