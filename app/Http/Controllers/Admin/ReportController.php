<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display daily report
     */
    public function daily(Request $request)
    {
        // Get date from request or default to today
        $date = $request->input('date', now()->format('Y-m-d'));
        $dateObj = Carbon::parse($date);

        // Query orders for the selected date
        $orders = Order::whereDate('created_at', $date)->get();

        // Calculate statistics
        $stats = [
            'date' => $dateObj->format('d M Y'),
            'total_online' => $orders->where('order_source', 'online')->count(),
            'total_offline' => $orders->where('order_source', 'offline')->count(),
            'total_revenue' => $orders->sum('total_price'),
            'total_weight' => $orders->sum('weight_kg'),
            'total_orders' => $orders->count(),
        ];

        return view('admin.reports.daily', compact('stats', 'date'));
    }
}
