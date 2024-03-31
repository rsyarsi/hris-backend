<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $url;
    protected $type;

    public function __construct($url, $type)
    {
        $this->url = $url;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $expired = now()->addMinute(60);
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->line('Welcome to RS YARSI Career!')
            ->line("Dear $notifiable->name")
            ->line("Thank you for $notifiable->type. To get started, please verify your email address by clicking the button below:")
            ->action('Verify Email Address', $this->url)
            ->line("This link will expire in $expired. If you did not register for an account with us, you can safely ignore this email.");
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
