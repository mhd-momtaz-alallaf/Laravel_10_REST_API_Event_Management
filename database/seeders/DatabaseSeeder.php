<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // the order is important

         \App\Models\User::factory(1000)->create(); // first we create users.

         $this->call(EventSeeder::class); // secondly we generate some events with random ouners.
         $this->call(AttendeeSeeder::class); // last thing we generate attendees for that events(every user attends events from 1 to 3).
    }
}
