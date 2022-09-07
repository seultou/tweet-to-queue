<?php

namespace App\Command\Console;

use App\Command\Publish;
use App\Entity\TwitterAccount\TwitterAccount;
use App\Twitter\Api;
use App\Twitter\Tweet\TweetCollection;
use App\Twitter\Twitter;
use Doctrine\ORM\Exception\ManagerException;
use Doctrine\Persistence\ManagerRegistry;
use Error;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(
    name: 'app:check-account',
    description: 'Check twitter accounts stored in database.',
    hidden: false
)]
class CheckTwitterAccount extends Command
{
    protected static $defaultName = 'app:check-account';

    private ManagerRegistry $doctrine;
    private Publish $publish;
    private Twitter $twitter;

    public function __construct(ManagerRegistry $doctrine, Publish $publish, Twitter $twitter)
    {
        $this->doctrine = $doctrine;
        $this->publish = $publish;
        $this->twitter = $twitter;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $manager = $this->doctrine->getManager();
        $tweets = new TweetCollection();
        $failure = 0;
        $totalPublished = 0;
        /** @var TwitterAccount $twitterAccount */
        foreach ($this->doctrine->getRepository(TwitterAccount::class)->findAll() as $twitterAccount) {
            $currentAccount = $this->twitter->request(
                Api\Request\TwitterAccount::byUsername($twitterAccount->username())
            );
            if ($twitterAccount->actualId() !== (string)$currentAccount->id()) {
                // current username has changed!
                $output->writeln('Wrong ID/username??? pair was: [ ' . $twitterAccount->actualId() . '/' . $currentAccount->id() . ' ]');
                break;
            }
            $tweets->append(
                $currentAccount,
                $this->twitter->request(
                    Api\Request\Tweet::byUserId(
                        $twitterAccount->actualId(),
                        ['since_id' => (int) $twitterAccount->lastTweetId()]
                    )
                )
            );
            if (count($tweets->get($currentAccount->id()) ?: []) > 0) {
                try {
                    $twitterAccount->updateLastTweetId($tweets->lastTweetId($currentAccount->id()) ?: null);
                    $manager->persist($twitterAccount);
                    $manager->flush();
                } catch (ManagerException|Error $exception) {
                    $failure = 1;
                    $output->writeln($exception->getTraceAsString());
                    break;
                }
            }
            $totalPublished = $totalPublished + count($tweets->get($currentAccount->id()) ?: []);
        }

        try {
            $this->publish->__invoke($tweets);
        } catch (Error|Throwable $e) {
            $failure = 1;
            $output->writeln('Could not send data to exchange!');
            $output->writeln($e->getTraceAsString());
        }
        if ($failure === 1) {
            return Command::FAILURE;
        }
        $output->writeln($totalPublished === 0 ? 'Nothing to re-publish at the moment.' : 'Successfully re-published!');
        $output->writeln(sprintf(
            '[%s] accounts checked and [%s] tweets re-published.',
            count($tweets->getKeys()),
            $totalPublished
        ));

        return Command::SUCCESS;
    }
}