<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\EventAssistanceCreated;
use App\Listeners\CreateAssistancesByEvent;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        EventAssistanceCreated::class => [
            CreateAssistancesByEvent::class,
        ],
    ];

    
    public function boot(): void
    {
        parent::boot(); // Llama al boot del EventServiceProvider de Laravel
    }
}
