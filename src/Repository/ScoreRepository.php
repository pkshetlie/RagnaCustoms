<?php

namespace App\Repository;

use App\Controller\WanadevApiController;
use App\Entity\Score;
use App\Entity\SongDifficulty;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    public function getOneByUserDiffVrOrNot(
        Utilisateur $user,
        SongDifficulty $songDiff,
        bool $isVR,
        bool $isOkod = false
    ): ?Score {
        $qb = $this
            ->createQueryBuilder('s')
            ->where('s.user = :user')
            ->setParameter('user', $user)
            ->andWhere('s.songDifficulty = :songDifficulty')
            ->setParameter('songDifficulty', $songDiff);

        if ($isVR) {
            $qb->andWhere('s.plateform IN (:plateformVr)')
                ->setParameter('plateformVr', WanadevApiController::VR_PLATEFORM);
        } else {
            if ($isOkod) {
                $qb->andWhere('s.plateform IN (:plateformVr)')
                    ->setParameter('plateformVr', WanadevApiController::OKOD_PLATEFORM);
            } else {
                $qb->andWhere('s.plateform IN (:plateformVr)')
                    ->setParameter('plateformVr', WanadevApiController::FLAT_PLATEFORM);
            }
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
