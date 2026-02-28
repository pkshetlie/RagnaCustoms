<?php

namespace App\Controller;

use App\Repository\SongRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SongPartialController extends AbstractController
{

    private $count = 8;

    public function latestSongs(SongRepository $songRepository): Response
    {
        $songs = $songRepository->createQueryBuilder("s")
            ->orderBy("s.lastDateUpload", 'DESC')
            ->addOrderBy("s.createdAt", 'DESC')
            ->where('s.isDeleted != true')
            ->andWhere('(s.programmationDate <= :now )')
            ->setParameter('now', (new DateTime()))
            ->andWhere('s.wip != true')
            ->andWhere('s.active = true')
            ->setMaxResults($this->count)
            ->setFirstResult(0)
            ->getQuery()->getResult();

        return $this->render('song_partial/index.html.twig', [
            'songs' => $songs,
        ]);
    }

    public function lastPlayed(SongRepository $songRepository, EntityManagerInterface $em): Response
    {


        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');

        $q = $em->createNativeQuery(
            '
        SELECT s.id
FROM score AS sc
JOIN song_difficulty AS sd ON sc.song_difficulty_id = sd.id
JOIN song AS s ON sd.song_id = s.id
JOIN (
    SELECT 
        user_id,
        song_difficulty_id,
        MAX(played_at) as max_played
    FROM score
    GROUP BY user_id, song_difficulty_id
) latest 
ON latest.user_id = sc.user_id
AND latest.song_difficulty_id = sc.song_difficulty_id
AND latest.max_played = sc.played_at
WHERE s.is_deleted != 1
AND s.programmation_date <= NOW()
AND s.wip != 1
AND s.active = 1
ORDER BY sc.played_at DESC
LIMIT 8',
            $rsm
        );

        $scores = $q->getArrayResult();

        $songs = array_map(function(array $score) use ($songRepository) {
            return $songRepository->find($score['id']);
        }, $scores);

        return $this->render('song_partial/index.html.twig', [
            'songs' => $songs,
        ]);
    }

    public function topRated(SongRepository $songRepository, $lastXDays = 99999): Response
    {
        $songs = $songRepository->createQueryBuilder("s")
            ->addSelect('s, SUM(IF(v.votes_indc IS NULL,0,IF(v.votes_indc = 0,-1,1))) AS HIDDEN sum_votes')
            ->leftJoin("s.voteCounters", 'v')
            ->orderBy("sum_votes", 'DESC')
            ->where('s.isDeleted != true')
            ->andWhere('s.isPrivate != true')
            ->andWhere('s.wip != true')
            ->andWhere('s.active = true')
            ->andWhere('s.programmationDate BETWEEN :date AND :now')
            ->setParameter('date', (new DateTime())->modify('-'.$lastXDays." days"))
            ->setParameter('now', (new DateTime()))
            ->groupBy('s.id')
            ->setMaxResults($this->count)
            ->setFirstResult(0)
            ->getQuery()->getResult();

        return $this->render('song_partial/index.html.twig', [
            'songs' => $songs,
        ]);
    }
}
