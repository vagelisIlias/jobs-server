<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     public function __construct(
        private string $subject,
        private string $status,
        private string $message,
        private string $url,
        private string $actionText,
        private string $lineText,
        private string $team
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
         return (new MailMessage)
            ->subject($this->subject)
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Account Status: ' . $this->status)
            ->action($this->actionText, url($this->url))
            ->line($this->lineText)
            ->salutation($this->team);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
