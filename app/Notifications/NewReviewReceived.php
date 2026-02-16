<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReviewReceived extends Notification
{
    use Queueable;

    public $review;

    /**
     * Create a new notification instance.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $stars = str_repeat('⭐', $this->review->rating);
        $commentSnippet = strlen($this->review->comment) > 50 
            ? substr($this->review->comment, 0, 50) . '...' 
            : $this->review->comment;

        return [
            'review_id' => $this->review->id,
            'order_id' => $this->review->order_id,
            'order_code' => $this->review->order->order_code ?? 'N/A',
            'customer_name' => $this->review->user->name ?? 'Customer',
            'rating' => $this->review->rating,
            'title' => 'Review Baru Diterima ' . $stars,
            'body' => "{$commentSnippet}",
            'icon' => 'heroicon-o-star',
            'status' => 'info',
            'url' => route('filament.admin.resources.reviews.index'),
        ];
    }
}
