<?php

namespace App\Services\Messenger\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use function Safe\json_encode;

final class Client
{
    private AMQPStreamConnection $connect;
    private string $exchange;
    private string $routingKey;

    public function __construct(
        string $amqpHost,
        string $amqpPort,
        string $amqpUsername,
        string $amqpPassword,
        string $amqpExchange,
        string $amqpRoutingKey,
    ) {
        $this->connect = new AMQPStreamConnection($amqpHost, $amqpPort, $amqpUsername, $amqpPassword);
        $this->exchange = $amqpExchange;
        $this->routingKey = $amqpRoutingKey;
    }

    public function get()
    {
        return $this->connect;
    }

    public function channel(null|int $channelId): AMQPChannel
    {
        return $this->connect->channel($channelId);
    }

    public function publish(array $info): void
    {
        $this->channel(null)->basic_publish(
            new AMQPMessage(json_encode($info)),
            $this->exchange,
            $this->routingKey
        );
    }
}