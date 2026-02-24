<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // Authorization: Ensure user owns the order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Check Status: Must be completed
        if ($order->status !== 'completed') {
            return back()->with('error', 'You can only review completed orders.');
        }

        // Check Existence: One review per order
        if ($order->review) {
            return back()->with('error', 'You have already reviewed this order.');
        }

        $review = Review::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Notify Admins
        $stars = str_repeat('⭐', $review->rating);
        $commentSnippet = strlen($review->comment) > 50 
            ? substr($review->comment, 0, 50) . '...' 
            : $review->comment;
            
        try {
            \App\Helpers\FilamentNotificationHelper::notifyAdmins(
                title: "Review Baru Diterima {$stars}",
                body: $commentSnippet,
                icon: 'heroicon-o-star',
                iconColor: 'info',
                actionUrl: route('admin.reviews.index'),
                actionLabel: 'View Reviews'
            );
        } catch (\Exception $notifEx) {
            \Illuminate\Support\Facades\Log::warning('Review notification failed: ' . $notifEx->getMessage());
        }

        return back()->with('success', 'Review submitted successfully!');
    }
}
