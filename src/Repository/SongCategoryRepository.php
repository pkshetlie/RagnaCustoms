<?php

namespace App\Repository;

use App\Entity\SongCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SongCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SongCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SongCategory[]    findAll()
 * @method SongCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongCategoryRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SongCategory::class);
    }
}
