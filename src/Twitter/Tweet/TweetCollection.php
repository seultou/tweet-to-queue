<?php

namespace App\Twitter\Tweet;

use App\Twitter\Api\Response\Response;
use App\Twitter\Api\Response\TwitterAccount;
use Doctrine\Common\Collections\ArrayCollection;

final class TweetCollection extends ArrayCollection
{
    public function append(TwitterAccount $account, Response $response): void
    {
        foreach ($response->data() as $tweetInfo) {
            $tweetInfo['author_username'] = $account->username();
            $tweetInfo['raw_includes'] = $response->includes();
            $this->set(
                $account->id(),
                array_merge(
                    $this->get($account->id()) ?? [],
                    [new Tweet($tweetInfo)]
                )
            );
        }
    }

    public function lastTweetId(int $actualId): null|int
    {
        $last = 0;
        foreach ($this->get($actualId) ?? [] as $tweet) {
            if ($last > $tweet->id()) {
                continue;
            }
            (int) $last = $tweet->id();
        }

        return $last;
    }

    public function render(): array
    {
        $output = [];
        foreach ($this->getKeys() as $key) {
            foreach ($this->get($key) as $tweet) {
                $output[$tweet->authorId()][] = $tweet->render();
            }
        }

        return $output;
    }
}