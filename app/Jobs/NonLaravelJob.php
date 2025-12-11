<?php

namespace App\Jobs;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;

class NonLaravelJob extends RabbitMQJob
{
    public $handlers = [
        'send_email' => TestJob::class
    ];

    public function payload(): array
    {
        // Assuming the message was sent as json encoded, here you could do:
        $data = json_decode($this->message->body);
        $action = $data['action'];
        $handler = $this->handlers[$action];

        return [
            'displayName' => $handler::class,
            'job' => 'Illuminate\\Queue\\CallQueuedHandler@call',
            'data' => [
                'commandName' => $handler::class,
                'command' => serialize(new $handler($data)),
            ],
        ];
    }
}
