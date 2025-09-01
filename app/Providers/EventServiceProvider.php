<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\Api\UserRegistered\UserRegisteredEventMessage;
use App\Listeners\UserRegistered\SendUserRegisteredNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegisteredEventMessage::class => [
            SendUserRegisteredNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
