<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderCreated extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Add 'mail' if email is configured
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Pesanan Baru Masuk!')
                    ->line('Pesanan baru telah diterima dari ' . $this->order->customer_name)
                    ->line('Kode Pesanan: ' . $this->order->order_code)
                    ->action('Lihat Pesanan', route('admin.orders.show', $this->order))
                    ->line('Silakan segera diproses.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_code' => $this->order->order_code,
            'customer_name' => $this->order->customer_name,
            'title' => 'Pesanan Baru Masuk! 🆕',
            'body' => "Pesanan {$this->order->order_code} dari {$this->order->customer_name} perlu diproses.",
            'icon' => 'heroicon-o-shopping-bag',
            'status' => 'info',
            'url' => route('filament.admin.resources.orders.view', $this->order),
        ];
    }
}
