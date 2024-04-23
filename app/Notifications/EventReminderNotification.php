<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification implements ShouldQueue // to tell laravel that this class have to run in the background
{
    use Queueable; // to run the jobs queue, we will need to run this command (php artisan queue:work) in a seprate cmd tab. 

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Event $event
    ) {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array // via witch notification channel (here its mail channel)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Reminder: You have an upcoming event!')
            ->action('View Event', route('events.show', $this->event->id))
            ->line(
                "The event {$this->event->name} starts at {$this->event->start_time}"
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'event_start_time' => $this->event->start_time
        ];
    }
}
