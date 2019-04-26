<?php

namespace App\Providers;

use App\Events\UserPasswordChangedEvent;
use App\Listeners\UserPasswordChangedListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserPasswordChangedEvent::class => [
            UserPasswordChangedListener::class,
        ],
    ];
}
