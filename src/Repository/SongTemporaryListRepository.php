<?php

namespace App\Repository;

use App\Entity\SongTemporaryList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SongTemporaryList|null find($id, $lockMode = null, $lockVersion = null)
 * @method SongTemporaryList|null findOneBy(array $criteria, array $orderBy = null)
 * @method SongTemporaryList[]    findAll()
 * @method SongTemporaryList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongTemporaryListRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SongTemporaryList::class);
    }
}
