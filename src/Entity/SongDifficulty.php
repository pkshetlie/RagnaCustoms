<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\SongDifficultyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ApiResource(
    operations: [new GetCollection()],
    normalizationContext: ['groups' => ['song_diff:get']],
    denormalizationContext: ['groups' => ['song_diff:get']],
    security: "is_granted('ROLE_USER')"
)]
#[ORM\Entity(repositoryClass: SongDifficultyRepository::class)]
class SongDifficulty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['song:get', 'song_diff:get'])]
    private $id;
    #[ORM\Column(type: 'float', nullable: true)]
    private $NotePerSecond;
    #[Groups(['song:get', 'song_diff:get'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $difficulty;
    #[ORM\ManyToOne(targetEntity: DifficultyRank::class, inversedBy: 'songDifficulties')]
    #[Groups(['song:get', 'song_diff:get'])]
    #[ApiProperty(fetchEager: false)]
    private $difficultyRank;
    #[ORM\Column(type: 'integer')]
    private $noteJumpMovementSpeed;
    #[ORM\Column(type: 'integer')]
    private $noteJumpStartBeatOffset;
    #[ORM\Column(type: 'integer', nullable: true)]
    private $notesCount;
    #[ORM\Column(type: 'float', nullable: true)]
    private $realMapDuration;

    #[Groups(['song:get', 'song_diff:get'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    #[ORM\ManyToOne(targetEntity: Song::class, inversedBy: 'songDifficulties', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[ApiProperty(fetchEager: true)]
    private $song;

    #[ORM\Column(type: 'float', nullable: false)]
    private $theoricalMaxScore;

    #[ORM\Column(type: 'float', nullable: false)]
    private $theoricalMinScore;

    #[Groups(['song:get', 'song_diff:get'])]
    #[ORM\Column(type: 'boolean', nullable: false)]
    private $isRanked;

    #[ORM\Column(type: 'float', nullable: true)]
    private $estAvgAccuracy;
    #[ORM\Column(type: 'float', nullable: true)]
    private $ppCurveMax;

    #[ORM\OneToMany(targetEntity: Score::class, mappedBy: 'songDifficulty')]
    private $scores;

    #[ORM\OneToMany(targetEntity: ScoreHistory::class, mappedBy: 'songDifficulty')]
    private $scoreHistories;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $wanadevHash;

    /**
     * @var Collection<int, SongDifficultyNotation>
     */
    #[ORM\OneToMany(mappedBy: 'songDifficultyNotation', targetEntity: SongDifficultyNotation::class, orphanRemoval: true)]
    private Collection $songDifficultyNotations;

    // /**
    //  * @var Collection<int, Tournament>
    //  */
    // #[ORM\ManyToMany(targetEntity: Tournament::class, inversedBy: 'songDifficulties')]
    // private Collection $tournaments;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->scoreHistories = new ArrayCollection();
        // $this->tournaments = new ArrayCollection();
        $this->songDifficultyNotations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getSong()->getName() . ", ".$this->getSong()->getAuthorName()." (by ".$this->getSong()->getMappersNames().") lvl " . $this->getDifficultyRank()->getLevel();
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): self
    {
        $this->song = $song;

        return $this;
    }

    #[Groups(['song:get', 'song_diff:get'])]
    public function getLevel(): ?int
    {
        return $this->getDifficultyRank()->getLevel();
    }

    public function getDifficultyRank(): ?DifficultyRank
    {
        return $this->difficultyRank;
    }

    public function setDifficultyRank(?DifficultyRank $difficultyRank): self
    {
        $this->difficultyRank = $difficultyRank;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getNoteJumpMovementSpeed(): ?int
    {
        return $this->noteJumpMovementSpeed;
    }

    public function setNoteJumpMovementSpeed(int $noteJumpMovementSpeed): self
    {
        $this->noteJumpMovementSpeed = $noteJumpMovementSpeed;

        return $this;
    }

    public function getNoteJumpStartBeatOffset(): ?int
    {
        return $this->noteJumpStartBeatOffset;
    }

    public function setNoteJumpStartBeatOffset(int $noteJumpStartBeatOffset): self
    {
        $this->noteJumpStartBeatOffset = $noteJumpStartBeatOffset;

        return $this;
    }

    public function getNotesCount(): ?int
    {
        return $this->notesCount;
    }

    public function setNotesCount(?int $notesCount): self
    {
        $this->notesCount = $notesCount;

        return $this;
    }

    public function getRealMapDuration(): ?float
    {
        return $this->realMapDuration;
    }

    public function setRealMapDuration(?float $realMapDuration): self
    {
        $this->realMapDuration = $realMapDuration;

        return $this;
    }

    public function getNotePerSecond(): ?float
    {
        return $this->NotePerSecond;
    }

    public function setNotePerSecond(?float $NotePerSecond): self
    {
        $this->NotePerSecond = $NotePerSecond;

        return $this;
    }

    public function getTheoricalMaxScore(): ?float
    {
        return $this->theoricalMaxScore;
    }

    public function setTheoricalMaxScore($theoricalMaxScore): self
    {
        $this->theoricalMaxScore = $theoricalMaxScore;

        return $this;
    }

    public function getTheoricalMinScore(): ?float
    {
        return $this->theoricalMinScore;
    }

    public function setTheoricalMinScore($theoricalMinScore): self
    {
        $this->theoricalMinScore = $theoricalMinScore;

        return $this;
    }

    public function getEstAvgAccuracy(): ?float
    {
        return $this->estAvgAccuracy;
    }

    public function setEstAvgAccuracy(?float $estAvgAccuracy): self
    {
        $this->estAvgAccuracy = $estAvgAccuracy;

        return $this;
    }

    public function getPPCurveMax(): ?float
    {
        return $this->ppCurveMax;
    }

    public function setPPCurveMax(?float $ppCurveMax): self
    {
        $this->ppCurveMax = $ppCurveMax;

        return $this;
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setSongDifficulty($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getSongDifficulty() === $this) {
                $score->setSongDifficulty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ScoreHistory[]
     */
    public function getScoreHistories(): Collection
    {
        return $this->scoreHistories;
    }

    public function addScoreHistory(ScoreHistory $scoreHistory): self
    {
        if (!$this->scoreHistories->contains($scoreHistory)) {
            $this->scoreHistories[] = $scoreHistory;
            $scoreHistory->setSongDifficulty($this);
        }

        return $this;
    }

    public function removeScoreHistory(ScoreHistory $scoreHistory): self
    {
        if ($this->scoreHistories->removeElement($scoreHistory)) {
            // set the owning side to null (unless already changed)
            if ($scoreHistory->getSongDifficulty() === $this) {
                $scoreHistory->setSongDifficulty(null);
            }
        }

        return $this;
    }


    public function isRanked(): ?bool
    {
        return $this->isRanked;
    }

    public function setIsRanked(?bool $isRanked): self
    {
        $this->isRanked = $isRanked;

        return $this;
    }

    public function getWanadevHash(): ?string
    {
        return $this->wanadevHash;
    }

    public function setWanadevHash(?string $wanadevHash): self
    {
        $this->wanadevHash = $wanadevHash;

        return $this;
    }

    public function isIsRanked(): ?bool
    {
        return $this->isRanked;
    }

    public function getDifficultyFile(string $baseDir)
    {
        $file = str_replace('info.dat',$this->difficulty."Standard.dat", $this->getSong()->getInfoDatFile());
        if(file_exists($baseDir.'/public'.$file)){
            return $file;
        }
        $file = str_replace('info.dat',$this->difficulty.".dat", $this->getSong()->getInfoDatFile());
        if(file_exists($baseDir.'/public'.$file)){
            return $file;
        }
        return '';
    }

    /**
     * @return Collection<int, Tournament>
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function addTournament(Tournament $event): static
    {
        if (!$this->tournaments->contains($event)) {
            $this->tournaments->add($event);
            $event->addSongDifficulty($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $event): static
    {
        if ($this->tournaments->removeElement($event)) {
            $event->removeSongDifficulty($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SongDifficultyNotation>
     */
    public function getSongDifficultyNotations(): Collection
    {
        return $this->songDifficultyNotations;
    }

    public function addSongDifficultyNotation(SongDifficultyNotation $songDifficultyNotation): static
    {
        if (!$this->songDifficultyNotations->contains($songDifficultyNotation)) {
            $this->songDifficultyNotations->add($songDifficultyNotation);
            $songDifficultyNotation->setSongDifficulty($this);
        }

        return $this;
    }

    public function removeSongDifficultyNotation(SongDifficultyNotation $songDifficultyNotation): static
    {
        if ($this->songDifficultyNotations->removeElement($songDifficultyNotation)) {
            // set the owning side to null (unless already changed)
            if ($songDifficultyNotation->getSongDifficulty() === $this) {
                $songDifficultyNotation->setSongDifficulty(null);
            }
        }

        return $this;
    }

}
