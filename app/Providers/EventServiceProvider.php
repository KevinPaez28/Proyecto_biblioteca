<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\EventAssistanceCreated;
use App\Listeners\CreateAssistancesByEvent;
use App\Models\Document\Documents;
use App\Models\Events\events;
use App\Models\Ficha\Ficha;
use App\Models\Profiles\Profiles;
use App\Models\Program\Program;
use App\Models\Reasons\reasons;
use App\Models\Rooms\rooms;
use App\Models\Schedules\Schedules;
use App\Models\Shifts\Shifts;
use App\Models\User\User;
use App\Observers\HistoryObserver;
use Spatie\Permission\Models\Role;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        EventAssistanceCreated::class => [
            CreateAssistancesByEvent::class,
        ],
    ];

    
    public function boot(): void
    {
        parent::boot(); 
        // User::observe(HistoryObserver::class);
        events::observe(HistoryObserver::class);
        Ficha::observe(HistoryObserver::class);
        Documents::observe(HistoryObserver::class);
        Profiles::observe(HistoryObserver::class);
        Program::observe(HistoryObserver::class);
        reasons::observe(HistoryObserver::class);
        rooms::observe(HistoryObserver::class);
        Schedules::observe(HistoryObserver::class);
        Shifts::observe(HistoryObserver::class);
        Role::observe(HistoryObserver::class);
    }
}
