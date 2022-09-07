<?php

namespace App\Command\Console;

use App\Entity\TwitterAccount\TwitterAccount;
use App\Twitter\Api;
use App\Twitter\Tweet\TweetCollection;
use App\Twitter\Twitter;
use Doctrine\Persistence\ManagerRegistry;
use ErrorException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-account',
    description: 'Add twitter accounts in database.',
    hidden: false
)]
class AddAccount extends Command
{
    private ManagerRegistry $doctrine;
    private Twitter $twitter;

    public function __construct(ManagerRegistry $doctrine, Twitter $twitter)
    {
        $this->doctrine = $doctrine;
        $this->twitter = $twitter;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:add-account')
            ->setDescription('Add twitter accounts in database')
            ->addOption(
                'usernames',
                'u',
                InputOption::VALUE_REQUIRED,
                'If set, enter the usernames'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $usernames = $input->getOption('usernames') ?? [];
        $manager = $this->doctrine->getManager();
        $ok = [];
        $notFound = [];
        $alreadyExist = [];
        $io = new SymfonyStyle($input, $output);
        foreach (explode(',', $usernames) as $username) {
            $username = trim($username);
            $exists = $this->doctrine->getRepository(TwitterAccount::class)->findOneBy(['username' => $username]);
            if ($exists instanceof TwitterAccount) {
                $alreadyExist[] = $username;
                continue;
            }
            try {
                $manager->persist(new TwitterAccount(
                    (string)($this->twitter->request(Api\Request\TwitterAccount::byUsername($username)))->id(),
                    $username,
                    null
                ));
            } catch (ErrorException $exception) {
                $notFound[] = $username;
                continue;
            }
            $ok[] = $username;
        }
        if (!empty($ok)) {
            $io->success(sprintf(
                'These usernames have been added/stored to be tracked: %s',
                implode(', ', $ok)
            ));
        }
        if (!empty($alreadyExist)) {
            $io->warning(sprintf(
                'These usernames have already been stored to be tracked: %s',
                implode(', ', $alreadyExist)
            ));
        }
        if (!empty($notFound)) {
            $io->error(sprintf(
                'These usernames have not been found and then stored to be tracked: %s',
                implode(', ', $notFound)
            ));
        }
        $manager->flush();

        return Command::SUCCESS;
    }

}