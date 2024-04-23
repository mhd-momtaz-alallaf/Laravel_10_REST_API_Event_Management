<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to all event attendees that the event will start soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = \App\Models\Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get(); // to get all events will start after 24 hours (the events with attendees and the users will attend).

        $eventCount = $events->count(); // count the resaults.
        $eventLabel = Str::plural('event', $eventCount); // 1 event 2 events....

        $this->info("Found $eventCount $eventLabel.");

        $events->each( // each will perform an arrow function to each $event item in the $events collection
            fn($event) => $event->attendees->each( // each event item have an attendees collection, so again we will perform an arrow function to each $attendee item
                fn($attendee) => $this->info("Notifying the user {$attendee->user->id}")
            )
        );


        $this->info('Reminder notifications sent successfully!');
    }
}
