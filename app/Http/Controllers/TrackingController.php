<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking');
    }

    public function search(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string|exists:orders,order_code',
        ]);

        $order = Order::where('order_code', $request->order_code)
            ->with(['orderTrackings' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        return view('tracking', compact('order'));
    }
}
