<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderFinished extends Notification
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
                    ->subject('Laundry Anda Selesai!')
                    ->line('Hore! Laundry Anda dengan kode ' . $this->order->order_code . ' sudah selesai dicuci.')
                    ->line('Silakan tunggu diantar atau Anda bisa menjemputnya sekarang.')
                    ->action('Lihat Pesanan', route('customer.orders.show', $this->order))
                    ->line('Terima kasih telah menggunakan jasa kami!');
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
            'title' => 'Laundry Selesai 🧺',
            'message' => 'Hore! Laundry kamu dengan kode ' . $this->order->order_code . ' sudah selesai dicuci.',
            'type' => 'finished'
        ];
    }
}
