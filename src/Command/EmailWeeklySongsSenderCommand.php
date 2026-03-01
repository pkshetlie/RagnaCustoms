<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Utilisateur;
use App\Enum\EEmail;
use App\Repository\SongRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

#[AsCommand(name: 'email:weekly_songs', description: 'Hello PhpStorm')]
class EmailWeeklySongsSenderCommand extends Command
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
        private readonly SongRepository $songRepository,
        private readonly UtilisateurRepository $utilisateurRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('patreon:weekly_email_sender')
            ->setDescription('Send weekly email to patreon subscribers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Only send emails on Sunday
        if ((new DateTime())->format('w') !== '0') {
            $output->writeln('Not Sunday, skipping email send.');

            return Command::SUCCESS;
        }


        $emailBody = $this->twig->render('emails/contents/weekly.html.twig', [
            'songs' => $this->songRepository->createQueryBuilder('s')
                ->where('s.isDeleted != true')
                ->andWhere('(s.programmationDate BETWEEN :lastweek AND :now )')
                ->setParameter('now', (new DateTime()))
                ->setParameter('lastweek', new DateTime()->modify('-7 days'))
                ->andWhere('s.wip != true')
                ->andWhere('s.active = true')
                ->orderBy('s.programmationDate', 'ASC')
                ->getQuery()->getResult(),
            'year' => date('Y'),
            'title' => 'Songs of the Week',
            'unsubscribe_url' => true
        ]);


        $page = 0;
        $count = 100;
        $countUser = 0;

        do {
            /** @var Utilisateur[] $users */
            $users = $this->utilisateurRepository->createQueryBuilder('u')
                ->orderBy('u.id', 'ASC')
                ->where('u.isVerified = true')
                ->andWhere('u.roles LIKE \'%ROLE_PREMIUM_%\'')
                ->setFirstResult($page * $count)
                ->setMaxResults($count)->getQuery()->getResult();

            if ($users) {
                $emailsSent = 0;

                foreach ($users as $user) {
                    if ($user->hasEmailPreference(EEmail::General_new_map)) {
                        $email = new Email()
                            ->from('noreply@ragnacustoms.com')
                            ->subject('Songs of the Week')
                            ->to($user->getEmail())
                            ->html($emailBody);
                        $this->mailer->send($email);
                        $emailsSent++;

                        // Rate limit: pause après chaque email (ex: 100ms)
                        usleep(100000); // 100ms = 100000 microseconds

                        // Pause plus longue tous les 10 emails (ex: 1 seconde)
                        if ($emailsSent % 10 === 0) {
                            sleep(1);
                            $output->writeln(sprintf('Sent %d emails, pausing...', $emailsSent));
                        }

                        $countUser += 1;
                    }
                }
            }

            $this->entityManager->clear();
            $page += 1;
        } while ($users);

        $email = new Email()
            ->from('noreply@ragnacustoms.com')
            ->subject('Résumé envoi')
            ->to('pierrick.pobelle@gmail.com')
            ->html("Mail de weekly envoyé à ".$countUser." utilisateurs");
        $this->mailer->send($email);

        return Command::SUCCESS;
    }
}
