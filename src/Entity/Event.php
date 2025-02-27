<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
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
    private ?string $scoreMath = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $priceDescription = null;

    #[ORM\Column]
    private ?int $maxPlayers = null;

    /**
     * @var Collection<int, ScoreEvent>
     */
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: ScoreEvent::class)]
    private Collection $scoreEvents;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'events')]
    #[ORM\Column(nullable: true)]
    private Collection $players;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[Gedmo\Slug(fields: ['label'])]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, SongDifficulty>
     */
    #[ORM\ManyToMany(targetEntity: SongDifficulty::class, inversedBy: 'events')]
    #[ORM\Column(nullable: true)]
    private Collection $songDifficulties;

    #[ORM\ManyToOne(inversedBy: 'ownedEvents')]
    private ?Utilisateur $owner = null;

    public function __construct()
    {
        $this->scoreEvents = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->songDifficulties = new ArrayCollection();
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
     * @return Collection<int, ScoreEvent>
     */
    public function getScoreEvents(): Collection
    {
        return $this->scoreEvents;
    }

    public function addScoreEvent(ScoreEvent $scoreEvent): static
    {
        if (!$this->scoreEvents->contains($scoreEvent)) {
            $this->scoreEvents->add($scoreEvent);
            $scoreEvent->setEvent($this);
        }

        return $this;
    }

    public function removeScoreEvent(ScoreEvent $scoreEvent): static
    {
        if ($this->scoreEvents->removeElement($scoreEvent)) {
            // set the owning side to null (unless already changed)
            if ($scoreEvent->getEvent() === $this) {
                $scoreEvent->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Utilisateur $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }

        return $this;
    }

    public function removePlayer(Utilisateur $player): static
    {
        $this->players->removeElement($player);

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
}
