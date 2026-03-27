<?php

namespace App\Jobs;

use App\Events\QueueNameGetMessage;
use App\Services\RabbitMqService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Wire\AMQPTable;

/**
 * Initializing RabbitMq queue, the queue name is 'queue_name'.
 */
class RabbitMqInitQueueJob implements ShouldQueue
{
    use Queueable;

    public $tries = 1;

    public function handle(): void
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
