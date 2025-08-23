<?php

namespace App\Repository;

use App\Entity\SongDifficultyNotation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SongDifficultyNotation>
 */
class SongDifficultyNotationRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SongDifficultyNotation::class);
    }
}
