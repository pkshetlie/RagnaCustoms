<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $banner = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $registrationStartAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $registrationEndAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(length: 255)]
    private ?string $scoreMath = '{pp}';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $priceDescription = null;

    #[ORM\Column]
    private ?int $maxPlayers = null;

    /**
     * @var Collection<int, TournamentScore>
     */
    #[ORM\OneToMany(mappedBy: 'Tournament', targetEntity: TournamentScore::class, orphanRemoval: true)]
    private Collection $tournamentScores;

  
    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[Gedmo\Slug(fields: ['label'])]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, SongDifficulty>
     */
    #[ORM\ManyToMany(targetEntity: SongDifficulty::class, inversedBy: 'tournaments')]
    #[ORM\Column(nullable: true)]
    private Collection $songDifficulties;

    #[ORM\ManyToOne(inversedBy: 'ownedTournaments')]
    private ?Utilisateur $owner = null;

    /**
     * @var Collection<int, TournamentPlayer>
     */
    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: TournamentPlayer::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $tournamentPlayers;

    public function __construct()
    {
        $this->tournamentScores = new ArrayCollection();
        $this->songDifficulties = new ArrayCollection();
        $this->tournamentPlayers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(string $banner): static
    {
        $this->banner = $banner;

        return $this;
    }

    public function getRegistrationStartAt(): ?\DateTimeImmutable
    {
        return $this->registrationStartAt;
    }

    public function setRegistrationStartAt(\DateTimeImmutable $registrationStartAt): static
    {
        $this->registrationStartAt = $registrationStartAt;

        return $this;
    }

    public function getRegistrationEndAt(): ?\DateTimeImmutable
    {
        return $this->registrationEndAt;
    }

    public function setRegistrationEndAt(\DateTimeImmutable $registrationEndAt): static
    {
        $this->registrationEndAt = $registrationEndAt;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getScoreMath(): ?string
    {
        return $this->scoreMath;
    }

    public function setScoreMath(string $scoreMath): static
    {
        $this->scoreMath = $scoreMath;

        return $this;
    }

    public function getPriceDescription(): ?string
    {
        return $this->priceDescription;
    }

    public function setPriceDescription(?string $priceDescription): static
    {
        $this->priceDescription = $priceDescription;

        return $this;
    }

    public function getMaxPlayers(): ?int
    {
        return $this->maxPlayers;
    }

    public function setMaxPlayers(int $maxPlayers): static
    {
        $this->maxPlayers = $maxPlayers;

        return $this;
    }

    /**
     * @return Collection<int, TournamentScore>
     */
    public function getTournamentScores(): Collection
    {
        return $this->tournamentScores;
    }

    public function addScoreTournament(TournamentScore $scoreTournament): static
    {
        if (!$this->tournamentScores->contains($scoreTournament)) {
            $this->tournamentScores->add($scoreTournament);
            $scoreTournament->setTournament($this);
        }

        return $this;
    }

    public function removeScoreTournament(TournamentScore $scoreTournament): static
    {
        if ($this->tournamentScores->removeElement($scoreTournament)) {
            // set the owning side to null (unless already changed)
            if ($scoreTournament->getTournament() === $this) {
                $scoreTournament->setTournament(null);
            }
        }

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, SongDifficulty>
     */
    public function getSongDifficulties(): Collection
    {
        return $this->songDifficulties;
    }

    public function addSongDifficulty(SongDifficulty $songDifficulty): static
    {
        if (!$this->songDifficulties->contains($songDifficulty)) {
            $this->songDifficulties->add($songDifficulty);
        }

        return $this;
    }

    public function removeSongDifficulty(SongDifficulty $songDifficulty): static
    {
        $this->songDifficulties->removeElement($songDifficulty);

        return $this;
    }

    public function getOwner(): ?Utilisateur
    {
        return $this->owner;
    }

    public function setOwner(?Utilisateur $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, TournamentPlayer>
     */
    public function getTournamentPlayers(): Collection
    {
        return $this->tournamentPlayers;
    }

    public function addTournamentPlayer(TournamentPlayer $tournamentPlayer): static
    {
        if (!$this->tournamentPlayers->contains($tournamentPlayer)) {
            $this->tournamentPlayers->add($tournamentPlayer);
            $tournamentPlayer->setTournament($this);
        }

        return $this;
    }

    public function removeTournamentPlayer(TournamentPlayer $tournamentPlayer): static
    {
        if ($this->tournamentPlayers->removeElement($tournamentPlayer)) {
            // set the owning side to null (unless already changed)
            if ($tournamentPlayer->getTournament() === $this) {
                $tournamentPlayer->setTournament(null);
            }
        }

        return $this;
    }
}
