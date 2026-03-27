<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Getting a message to RabbitMq, the queue name is 'queue_name'. Listener is 'QueueNameMessageProcessing'
 * @property AMQPMessage $msg
 */
class QueueNameGetMessage
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public AMQPMessage $msg)
    {
    }

}
