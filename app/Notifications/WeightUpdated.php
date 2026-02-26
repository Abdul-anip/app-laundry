<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeightUpdated extends Notification
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
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Berat Laundry Anda Telah Ditimbang')
                    ->line('Laundry Anda dengan kode ' . $this->order->order_code . ' telah ditimbang.')
                    ->line('Berat: ' . floatval($this->order->weight_kg) . ' Kg')
                    ->line('Total: Rp ' . number_format($this->order->total_price, 0, ',', '.'))
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
            'order_id'   => $this->order->id,
            'order_code' => $this->order->order_code,
            'title'      => 'Berat Laundry Ditimbang ⚖️',
            'message'    => 'Laundry kamu dengan kode ' . $this->order->order_code
                          . ' telah ditimbang: ' . floatval($this->order->weight_kg) . ' Kg. '
                          . 'Total: Rp ' . number_format($this->order->total_price, 0, ',', '.'),
            'type'       => 'weight_updated',
        ];
    }
}
