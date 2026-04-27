<?php

namespace App\Repository;

use App\Entity\ScoreHistory;
use App\Entity\SongDifficulty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method ScoreHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScoreHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScoreHistory[]    findAll()
 * @method ScoreHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreHistoryRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScoreHistory::class);
    }

    public function findByUserAndDifficulty(
        ?UserInterface $user,
        SongDifficulty $songDifficulty
    ): array {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->leftJoin('s.songDifficulty', 'sd')
            ->where('s.user = :user')
            ->andWhere('sd.difficultyRank = :level')
            ->setParameter('user', $user)
            ->setParameter('level', $songDifficulty->getDifficultyRank())->getQuery()->getResult();
    }
}
