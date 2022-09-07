<?php

namespace App\Command;

use App\Common\ExitCode;
use App\Services\Messenger\RabbitMQ\Client;
use App\Twitter\Tweet\TweetCollection;
use DateTimeZone;
use Safe\DateTimeImmutable;

class Publish
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(TweetCollection $tweets): int
    {
        foreach ($tweets as $tweetList) {
            foreach ($tweetList as $tweet) {
                $this->client->publish([
                    'id' => $tweet->id(),
                    'created_at' => $tweet->createdAt(),
                    'author_id' => $tweet->authorId(),
                    'author_username' => $tweet->authorUsername(),
                    'text' => $tweet->text(),
                    'media' => $tweet->media()->toArray(),
                    'snapshotist_publish_utc_date_atom' => (new DateTimeImmutable('now', new DateTimeZone('UTC')))
                        ->format(DATE_ATOM),
                ]);
            }
        }

        return ExitCode::SUCCESS;
    }
}