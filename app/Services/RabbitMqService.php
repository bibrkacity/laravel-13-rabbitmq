<?php

namespace App\Services;

use App\Events\QueueNameGetMessage;
use Illuminate\Support\Facades\Log;
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

    public static function startWaiting(): void
    {
        $connection = RabbitMqService::initConnection();
        $channel = $connection->channel();
        $channel->queue_declare('queue_name', false, true, false, false, false, new AMQPTable(['x-queue-type' => 'quorum']));

        $callback = function ($msg) {
            QueueNameGetMessage::dispatch($msg);
        };

        $channel->basic_consume('queue_name', '', false, true, false, false, $callback);

        try {
            $channel->consume(); // Starting the process on the server
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
        }

        $channel->close();
        $connection->close();
    }
}
