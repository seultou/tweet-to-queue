<?php

namespace spec\App\Services\Messenger\RabbitMQ;

use App\Services\Messenger\RabbitMQ\Client;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Dotenv\Dotenv;

class ClientSpec extends ObjectBehavior
{
    function let()
    {
        (new Dotenv(false))->loadEnv(dirname(__DIR__) . '\\..\\..\\..\\..\\.env');
        $this->beConstructedWith(
            $_ENV['AMQP_HOST'],
            $_ENV['AMQP_PORT'],
            $_ENV['AMQP_USERNAME'],
            $_ENV['AMQP_PASSWORD'],
            $_ENV['AMQP_EXCHANGE'],
            $_ENV['AMQP_ROUTING_KEY'],
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    function it_returns_an_instance_of_AMQPStreamConnection()
    {
        $this->get()->shouldBeAnInstanceOf(AMQPStreamConnection::class);
    }

    function it_returns_an_instance_of_AMQPChannel_when_id_is_null()
    {
        $this->channel(null)->shouldBeAnInstanceOf(AMQPChannel::class);
    }

    function it_returns_an_instance_of_AMQPChannel_when_id_is_int_value()
    {
        $this->channel(2)->shouldBeAnInstanceOf(AMQPChannel::class);
    }
}
