<?php

namespace App\Repository;

use App\Entity\DifficultyRank;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DifficultyRank|null find($id, $lockMode = null, $lockVersion = null)
 * @method DifficultyRank|null findOneBy(array $criteria, array $orderBy = null)
 * @method DifficultyRank[]    findAll()
 * @method DifficultyRank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DifficultyRankRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DifficultyRank::class);
    }
}
