<?php

namespace App\Console\Commands;

use App\Jobs\RabbitMqInitQueueJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:rabbit')]
#[Description('Dispatches the job RabbitMqInitQueueJob to query "rabbitmq_test"')]
class RunRabbitMqWaiting extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $queue = config('database.connections.rabbitmq.queue');
        RabbitMqInitQueueJob::dispatch()->onQueue($queue);
    }
}
