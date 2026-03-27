<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class RabbitMqService
{
    public static function initConnection(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            config('database.connections.rabbitmq.host'),
            config('database.connections.rabbitmq.port'),
            config('database.connections.rabbitmq.username'),
            config('database.connections.rabbitmq.password')
        );
    }

    public static function sendMessage(string $message, string $queueName): void
    {
        $connection = self::initConnection();
        $channel = $connection->channel();
        $channel->queue_declare($queueName, false, true, false, false, false, new AMQPTable(['x-queue-type' => 'quorum']));

        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, '', $queueName);

        $channel->close();
        $connection->close();
    }
}
