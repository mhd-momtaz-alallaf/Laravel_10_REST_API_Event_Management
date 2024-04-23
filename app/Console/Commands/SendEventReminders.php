<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        $this->info('Reminder notifications sent successfully!');
    }
}
