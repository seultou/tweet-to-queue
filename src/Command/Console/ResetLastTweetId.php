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
    name: 'app:reset-last-tweet-id',
    description: 'Reset/null twitter_account.last_tweet_id value for given username(s)',
    hidden: false
)]
class ResetLastTweetId extends Command
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
            ->setName('app:reset-last-tweet-id')
            ->setDescription('Reset/null twitter_account.last_tweet_id value for given username(s)')
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
        $io = new SymfonyStyle($input, $output);
        foreach (explode(',', $usernames) as $username) {
            $username = trim($username);
            $twitterAccount = $this->doctrine->getRepository(TwitterAccount::class)->findOneBy(['username' => $username]);
            if (!$twitterAccount instanceof TwitterAccount) {
                continue;
            }
            $ok[] = $username;
            $twitterAccount->resetLastTweetId();
            $manager->persist($twitterAccount);
        }
        $io->info(sprintf(
            'twitter_account.last_tweet_id records have been nulled for these usernames: %s',
            implode(', ', $ok)
        ));
        $manager->flush();

        return Command::SUCCESS;
    }

}