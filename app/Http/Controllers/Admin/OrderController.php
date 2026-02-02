<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['user', 'service', 'bundle'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'service', 'bundle', 'promo', 'orderTrackings', 'review']);
        
        // Mark relevant notifications as read
        if (auth()->check()) {
            auth()->user()->unreadNotifications->where('data.order_id', $order->id)->markAsRead();
        }
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,pickup,process,finished,delivered',
        ]);

        try {
            DB::beginTransaction();

            $originalStatus = $order->status;

            // Update Order Status
            $order->update([
                'status' => $request->status,
                'payment_status' => $request->status === 'delivered' ? 'paid' : $order->payment_status,
            ]);

            // Create Order Tracking
            OrderTracking::create([
                'order_id' => $order->id,
                'status' => $request->status,
                'description' => 'Status updated to ' . ucfirst($request->status) . ' by Admin',
            ]);

            // Trigger Notifications
            if ($originalStatus !== 'finished' && $request->status === 'finished') {
                if ($order->user) {
                    $order->user->notify(new \App\Notifications\OrderFinished($order));
                }
            }

            if ($originalStatus !== 'delivered' && $request->status === 'delivered') {
                if ($order->order_source === 'online' && $order->user) {
                    $order->user->notify(new \App\Notifications\OrderDelivered($order));
                }
            }

            // Point System Logic: Add points if status is 'finished' and was not previously 'finished'
            if ($request->status === 'finished' && $originalStatus !== 'finished') {
                $points = floor($order->weight_kg);
                // Ensure we have a user and points > 0
                if ($points > 0 && $order->user) {
                    $order->user->increment('points', $points);
                    
                    OrderTracking::create([
                        'order_id' => $order->id,
                        'status' => 'point_added',
                        'description' => "Customer earned {$points} points",
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order status updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update status.']);
        }
    }

    /**
     * Generate and download PDF receipt for order
     */
    public function printReceipt(Order $order)
    {
        $order->load(['user', 'service', 'bundle', 'promo']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.receipt', compact('order'));
        
        return $pdf->download('struk-' . $order->order_code . '.pdf');
    }
}
