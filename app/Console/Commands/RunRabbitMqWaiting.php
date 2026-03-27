<?php

namespace App\Console\Commands;

use App\Jobs\RabbitMqInitQueueJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

#[Signature('app:rabbit')]
#[Description('Run waiting for the messages to RabbitMq query "queue_name"')]
class RunRabbitMqWaiting extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $queue = config('database.connections.rabbitmq.queue');
        Artisan::call('queue:work', ['--queue' => $queue, '--timeout' => '0']);
        RabbitMqInitQueueJob::dispatch()->onQueue($queue);
    }
}
