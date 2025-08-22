<?php

namespace App\Repository;

use App\Entity\Song;
use App\Entity\Utilisateur;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends AbstractEntityRepositoryWithTools
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function getVotePublicOrMine(?Utilisateur $user, Song $song): ArrayCollection
    {
        $qb = $this->createQueryBuilder('f');
        $qb
            ->where(
                $qb->expr()->andX(
                    'f.song = :song',
                    'f.isPublic = true',
                    'f.isModerated = true',
                    'f.feedback is not null',
                )
            )
            ->orWhere(
                $qb->expr()->andX(
                    'f.song = :song',
                    'f.user = :user',
                    'f.feedback is not null',
                )
            )
            ->setParameter('song', $song)
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }
}
