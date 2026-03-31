<?php

namespace App\Services;

use App\Events\QueueNameGetMessage;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

/**
 * RabbitMq service class.
 */
class RabbitMqService
{
    /**
     * Initialize the RabbitMQ connection.
     * @return AMQPStreamConnection
     * @throws \Exception
     */
    public static function initConnection(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            config('database.connections.rabbitmq.host'),
            config('database.connections.rabbitmq.port'),
            config('database.connections.rabbitmq.username'),
            config('database.connections.rabbitmq.password')
        );
    }

    /**
     * Send a message to the RabbitMQ queue.
     * @param string $message The message to send.
     * @param string $queueName The name of the RabbitMQ queue to send the message to.
     * @return void
     * @throws \Exception
     */
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

    /**
     * Use in the RabbitMqInitQueueJob to start waiting for messages.
     * @return void
     * @throws \Exception
     */
    public static function startWaiting(): void
    {
        $connection = self::initConnection();
        $channel = $connection->channel();
        $channel->queue_declare(
            'queue_name',
            false,
            true,
            false,
            false,
            false,
            new AMQPTable(['x-queue-type' => 'quorum'])
        );

        $callback = function ($msg) {
            QueueNameGetMessage::dispatch($msg);
        };

        $channel->basic_consume(
            'queue_name',
            '',
            false,
            true,
            false,
            false,
            $callback
        );
        try {
            $channel->consume(); // Starting the process on the RabbitMQ server
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
        }
        $channel->close();
        $connection->close();
    }
}
