<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SystemAlertNotification extends Notification
{
    use Queueable;

    public string $title;
    public string $body;
    public ?string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $body, ?string $url = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for the database.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body'  => $this->body,
            'url'   => $this->url,
        ];
    }
}
