<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('update-event',function($user,Event $event){ 
            return $user->id === $event->user_id; // to ensure that only the owner of the event can update the event.
        });

        Gate::define('delete-attendee',function($user,Event $event ,Attendee $attendee){
            return $user->id === $event->user_id || $user->id === $attendee->user_id; // only the owner of the event or the attendee itslef can delete the attendee.
        });
    }
}
