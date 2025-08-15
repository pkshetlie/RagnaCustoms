<?php

namespace App\Repository;

use App\Entity\FollowMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FollowMapper>
 *
 * @method FollowMapper|null find($id, $lockMode = null, $lockVersion = null)
 * @method FollowMapper|null findOneBy(array $criteria, array $orderBy = null)
 * @method FollowMapper[]    findAll()
 * @method FollowMapper[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowMapperRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FollowMapper::class);
    }




    // /**
    //  * @return FollowMapper[] Returns an array of FollowMapper objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FollowMapper
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
