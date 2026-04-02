<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspiciousLoginNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $email,
        private readonly int $count,
        private readonly string $ip,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Security Alert — Multiple Failed Logins')
            ->line("{$this->email} has had {$this->count} failed login attempts in the last 15 minutes from IP {$this->ip}.")
            ->line('If this is suspicious, consider deactivating this account in the Staff panel.');
    }
}
