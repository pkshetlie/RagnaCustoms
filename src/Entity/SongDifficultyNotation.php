<?php

namespace App\Entity;

use App\Repository\SongDifficultyNotationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongDifficultyNotationRepository::class)]
class SongDifficultyNotation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'songDifficultyNotations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SongDifficulty $songDifficulty = null;

    #[ORM\ManyToOne(inversedBy: 'songDifficultyNotations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $user = null;

    #[ORM\Column]
    private ?int $note = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSongDifficulty(): ?SongDifficulty
    {
        return $this->songDifficulty;
    }

    public function setSongDifficulty(?SongDifficulty $songDifficulty): static
    {
        $this->songDifficulty = $songDifficulty;

        return $this;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }
}
