<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Support\Facades\Log;
use Throwable;

class AttachRoleToUser
{
    public int $tries = 2;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        Log::debug('Запуск слушателя');
        $event->user->roles()->attach(2);
        Log::debug($event->user);
    }

    public function failed(UserCreated $event, Throwable $exception): void
    {
        Log::error('Ошибка при обработке события: ' . $exception->getMessage());
    }
}
