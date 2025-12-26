<?php

namespace App\Jobs;

use Override;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;

/**
 * @psalm-suppress UnusedClass
 */
final class NonLaravelJob extends RabbitMQJob
{
    /**
     * @psalm-suppress MissingPropertyType
     */
    public $handlers = [
        'send_email' => TestJob::class
    ];

    #[Override]
    /**
     * @psalm-suppress MissingPropertyType
     */
    public function payload(): array
    {
        // Assuming the message was sent as json encoded, here you could do:
        $data = json_decode($this->message->getBody());
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
