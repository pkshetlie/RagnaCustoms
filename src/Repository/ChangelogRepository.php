<?php

namespace App\Repository;

use App\Entity\Changelog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Changelog>
 */
class ChangelogRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Changelog::class);
    }

    public function findNoRead(?UserInterface $user): array
    {
        if ($user) {
            return $this->createQueryBuilder('c')
                ->andWhere('c.id NOT IN (:changes)')
                ->andWhere('c.isWip = false')
                ->setParameter('changes', $user->getChangelogs()->count() != 0 ? $user->getChangelogs() : [0])
                ->orderBy('c.createdAt', 'ASC')
                ->getQuery()->getResult();
        }

        return [];
    }

    public function findNoReadOrWip(UserInterface $user): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->andWhere(
                $qb->expr()->orX(
                    'c.id NOT IN (:changes)',
                    'c.isWip = true',
                )
            )
            ->setParameter('changes', $user->getChangelogs()->count() != 0 ? $user->getChangelogs() : [0])
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()->getResult();

    }
}
