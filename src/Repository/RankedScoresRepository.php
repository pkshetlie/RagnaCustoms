<?php

namespace App\Repository;

use App\Entity\RankedScores;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RankedScores|null find($id, $lockMode = null, $lockVersion = null)
 * @method RankedScores|null findOneBy(array $criteria, array $orderBy = null)
 * @method RankedScores[]    findAll()
 * @method RankedScores[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RankedScoresRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RankedScores::class);
    }
}
