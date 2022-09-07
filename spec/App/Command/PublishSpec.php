<?php

namespace spec\App\Command;

use App\Command\Publish;
use App\Common\ExitCode;
use App\Services\Messenger\RabbitMQ\Client;
use App\Twitter\Tweet\Tweet;
use App\Twitter\Tweet\TweetCollection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Dotenv\Dotenv;

class PublishSpec extends ObjectBehavior
{
    function let()
    {
        (new Dotenv(false))->loadEnv(dirname(__DIR__) . '\\..\\..\\.env');
        $this->beConstructedWith(
            new Client(
                $_ENV['AMQP_HOST'],
                $_ENV['AMQP_PORT'],
                $_ENV['AMQP_USERNAME'],
                $_ENV['AMQP_PASSWORD'],
                $_ENV['AMQP_EXCHANGE'],
                $_ENV['AMQP_ROUTING_KEY'],
            )
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Publish::class);
    }

    function it_triggers_TypeError_when_TweetCollection_is_not_provided()
    {
        $this->shouldThrow(\TypeError::class)->during('__invoke', [['random' => 'array']]);
    }

    function it_publishes_elements_from_TweetCollection()
    {
        $this->__invoke(new TweetCollection([456 => new Tweet([
            'id' => 123,
            'created_at' => '2022-12-31 23:59:59',
            'author_id' => 456,
            'author_username' => 'Auth0r',
            'text' => 'Random text',
            'media' => [],
            'snapshotist_publish_utc_date_atom' => (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                    ->format(DATE_ATOM),
        ])]))->shouldReturn(ExitCode::SUCCESS);
    }
}
