<?php

namespace App\Jobs;

use App\Services\RabbitMqService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Initializing RabbitMq queue, the queue name is 'queue_name'.
 */
class RabbitMqInitQueueJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        RabbitMqService::startWaiting();
    }
}
