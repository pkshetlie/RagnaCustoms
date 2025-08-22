<?php

namespace App\Repository;

use App\Entity\SongDifficulty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SongDifficulty|null find($id, $lockMode = null, $lockVersion = null)
 * @method SongDifficulty|null findOneBy(array $criteria, array $orderBy = null)
 * @method SongDifficulty[]    findAll()
 * @method SongDifficulty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongDifficultyRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SongDifficulty::class);
    }

    /**
     * @return SongDifficulty[]
     */
    public function getRanked(): array
    {
        $qb = $this->createQueryBuilder('sd')
            ->leftJoin('sd.song', 's')
            ->andWhere("sd.isRanked = true")
            ->orderBy('s.name', 'ASC');

       return $qb->getQuery()->getResult();
    }
}
