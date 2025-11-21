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
    public function __construct(private readonly SongDifficultyNotationRepository $songDifficultyNotationRepository)
    {
    }

    public function setScore(SongDifficulty $songDifficulty, ?Utilisateur $utilisateur, int $note)
    {
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

    public function getUserCommunityNote(mixed $difficulty, ?Utilisateur $user)
    {

        if ($user === null) {
            return [];
        }

        $notation = $this->songDifficultyNotationRepository->findOneBy([
            'songDifficulty' => $difficulty,
            'user' => $user,
        ]);

        if ($notation === null) {
            return $difficulty->getDifficultyRank()->getLevel();
        }

        return (int)($notation->getNote());
    }

}
