<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

#[Signature('app:rabbit-mq-monitor')]
#[Description('Run queue:monitor command and check if the queue is fail. If fail logs an error')]
class RabbitMqMonitor extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $queue = config('database.connections.rabbitmq.queue');
        Artisan::call('queue:monitor --json '.$queue);
        $output = Artisan::output();
        $json = json_decode($output, true);
        $size = (int)($json[0]['size']);

        if ($size == 0) {
            Log::error('The queue "'.$queue.'" crashed');
        }
    }
}
