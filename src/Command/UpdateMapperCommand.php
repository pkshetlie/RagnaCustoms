<?php

namespace App\Command;

use App\Entity\Mapper;
use App\Entity\Utilisateur;
use App\Repository\SongRepository;
use App\Repository\UtilisateurRepository;
use App\Service\MapperService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'mapper:update',
    description: 'Update Mapper List',
)]
class UpdateMapperCommand extends Command
{
    public function __construct(
        private readonly UtilisateurRepository $utilisateurRepository,
        private readonly SongRepository $songRepository,
        private readonly MapperService $mapperService,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConnection()->executeStatement('UPDATE utilisateur SET is_mapper = 0;');
        $this->entityManager->getConnection()->executeStatement('UPDATE utilisateur u SET u.is_mapper = 1 WHERE u.id IN (SELECT DISTINCT su.utilisateur_id FROM song_utilisateur su);');
        $this->entityManager->getConnection()->executeStatement('TRUNCATE TABLE mapper');

        /** @var Utilisateur[] $mappers */
        $qb = $this->utilisateurRepository->createQueryBuilder("u")
            ->where('u.isMapper = 1')
            ->orderBy('u.id', 'desc')
            ->setFirstResult(0)
            ->setMaxResults(25);
        $users = $qb->getQuery()->getResult();

        $i = 0;

        while ($users != null) {
            /** @var Utilisateur $user */
            foreach ($users as $user) {
                $userSongsStats = $this
                    ->songRepository
                    ->createQueryBuilder('s')
                    ->select('COUNT(s) AS count')
                    ->addSelect('SUM(s.voteUp) AS vote_up')
                    ->addSelect('SUM(s.voteDown) AS vote_down')
                    ->addSelect(
                        'COALESCE(
                                ROUND(
                                    SUM(s.totalVotes) / NULLIF(SUM(s.countVotes), 0),
                                2),
                            0) AS avg_rating
                        '
                    )
                    ->leftJoin('s.mappers', 'mapper')
                    ->where('mapper.id = :mapper')
                    ->setParameter('mapper', $user->getId())
                    ->getQuery()->getArrayResult();

                if ($userSongsStats[0]['count'] == 0) {
                    continue;
                }

                $result = $this->songRepository->createQueryBuilder('s')
                    ->select('ct.label AS label')
                    ->join('s.mappers', 'm')
                    ->join('s.categoryTags', 'ct')
                    ->where('m.id = :mapper')
                    ->groupBy('ct.id')
                    ->setMaxResults(4)
                    ->setParameter('mapper', $user->getId())
                    ->getQuery()
                    ->getArrayResult();

                $categories = array_map(fn($r) => $r['label'], $result);

                if (empty($categories)) {
                    $categories = ['None'];
                }
                $mapper = new Mapper()
                    ->setName($user->getMapperName())
                    ->setAvgRating($userSongsStats[0]['avg_rating'] ?? 0)
                    ->setCategories($categories)
                    ->setFollows($user->getFollowers()->count())
                    ->setMapCount($userSongsStats[0]['count'] ?? 0)
                    ->setVotesDown($userSongsStats[0]['vote_down'] ?? 0)
                    ->setVotesUp($userSongsStats[0]['vote_up'] ?? 0);
                $this->entityManager->persist($mapper);
            }

            $this->entityManager->flush();
            $i++;
            $users = $qb->setFirstResult($i * 25)->getQuery()->getResult();
        }

        return Command::SUCCESS;
    }
}
