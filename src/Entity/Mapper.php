<?php

namespace App\Entity;

use App\Repository\MapperRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MapperRepository::class)]
class Mapper
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $follows = null;

    #[ORM\Column]
    private ?int $mapCount = null;

    #[ORM\Column]
    private ?int $votesUp = null;

    #[ORM\Column]
    private ?int $votesDown = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?string $avgRating = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $categories = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFollows(): ?int
    {
        return $this->follows;
    }

    public function setFollows(int $follows): static
    {
        $this->follows = $follows;

        return $this;
    }

    public function getMapCount(): ?int
    {
        return $this->mapCount;
    }

    public function setMapCount(int $mapCount): static
    {
        $this->mapCount = $mapCount;

        return $this;
    }

    public function getVotesUp(): ?int
    {
        return $this->votesUp;
    }

    public function setVotesUp(int $votesUp): static
    {
        $this->votesUp = $votesUp;

        return $this;
    }

    public function getVotesDown(): ?int
    {
        return $this->votesDown;
    }

    public function setVotesDown(int $votesDown): static
    {
        $this->votesDown = $votesDown;

        return $this;
    }

    public function getAvgRating(): ?string
    {
        return $this->avgRating;
    }

    public function setAvgRating(string $avgRating): static
    {
        $this->avgRating = $avgRating;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }
}
