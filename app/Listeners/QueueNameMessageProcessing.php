<?php

namespace App\Listeners;

use App\Events\QueueNameGetMessage;
use Illuminate\Support\Facades\Log;

/**
 * Processing a message from RabbitMq, the queue name is 'queue_name'
 */
class QueueNameMessageProcessing
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event "getting a message to RabbitMq, the queue name is 'queue_name'".
     * Simple logic for educational purposes
     */
    public function handle(QueueNameGetMessage $event): void
    {
        Log::info("Got the message into RabbitMQ, queue='queue_name': " . $event->msg->getBody());
    }
}
