<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankedScoresRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\StatisticService;

#[ORM\Index(name: 'idx_ranked_scores_platform_score', columns: ['plateform', 'total_pp_score'])]
#[ORM\Index(name: 'idx_ranked_scores_platform_user', columns: ['plateform', 'user_id'])]
#[ORM\Index(name: 'idx_ranked_scores_user', columns: ['user_id'])]
#[ORM\Entity(repositoryClass: RankedScoresRepository::class)]
class RankedScores
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'rankedScores')]
    #[ORM\JoinColumn(nullable: false)]
    private Utilisateur $user;

    #[ORM\Column(type: 'float', nullable: true)]
    private float $totalPpScore;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $plateform = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTotalPpScore(): ?float
    {
        return $this->totalPpScore;
    }

    public function setTotalPpScore(?float $totalPPScore): self
    {
        $this->totalPpScore = $totalPPScore;

        return $this;
    }

    public function getTimeAgoShort()
    {
      return StatisticService::dateDisplayedShort($this->updatedAt);
    }

    public function getPlateform(): ?string
    {
        return $this->plateform;
    }

    public function setPlateform(?string $plateform): static
    {
        $this->plateform = $plateform;

        return $this;
    }
}
