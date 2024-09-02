<?php

namespace App\Notifications;

use App\Models\Payout;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PayoutInitiated extends Notification
{
    use Queueable;

    protected $payout;

    public function __construct(Payout $payout)
    {
        $this->payout = $payout;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payout Initiated')
            ->line('A payout has been initiated for your completed deliveries.')
            ->line('Amount: $' . $this->payout->amount)
            ->line('Status: ' . $this->payout->status)
            ->line('We will notify you once the payout has been processed.');
    }
}