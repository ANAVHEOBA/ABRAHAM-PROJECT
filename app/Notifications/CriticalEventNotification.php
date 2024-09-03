<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CriticalEventNotification extends Notification
{
    private $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Critical Event: ' . $this->event->title)
            ->line($this->event->message)
            ->action('View Details', url('/'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'critical_event',
            'title' => $this->event->title,
            'message' => $this->event->message,
        ];
    }
}
