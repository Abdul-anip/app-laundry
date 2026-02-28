<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('customer.tracking.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string|exists:orders,order_code',
        ]);

        $order = Order::where('order_code', $request->order_code)
            ->where('user_id', auth()->id()) // Ensure they only track their own orders
            ->with(['orderTrackings' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->first();

        if (!$order) {
            return back()->withInput()->withErrors(['order_code' => 'Pesanan tidak ditemukan atau bukan milik Anda.']);
        }

        return view('customer.tracking.index', compact('order'));
    }
}
