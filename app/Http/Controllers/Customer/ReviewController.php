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

        // Check Status: Must be finished or delivered
        if (!in_array($order->status, ['finished', 'delivered'])) {
            return back()->with('error', 'You can only review finished orders.');
        }

        // Check Existence: One review per order
        if ($order->review) {
            return back()->with('error', 'You have already reviewed this order.');
        }

        Review::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
