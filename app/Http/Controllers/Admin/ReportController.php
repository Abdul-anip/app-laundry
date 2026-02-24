<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : now();
        $dateFormatted = $date->format('Y-m-d');

        $orders = Order::with(['service', 'bundle'])
            ->whereDate('created_at', $date)
            ->where('status', '!=', 'pending')
            ->orderBy('created_at')
            ->get();

        $summary = [
            'total_orders'  => $orders->count(),
            'total_revenue' => $orders->sum('total_price'),
            'total_weight'  => $orders->sum('weight_kg'),
            'online_orders' => $orders->where('order_source', 'online')->count(),
            'offline_orders'=> $orders->where('order_source', 'offline')->count(),
        ];

        return view('admin.reports.index', compact('orders', 'summary', 'date', 'dateFormatted'));
    }

    public function downloadPdf(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : now();
        $dateFormatted = $date->format('Y-m-d');

        $orders = Order::with(['service', 'bundle'])
            ->whereDate('created_at', $date)
            ->where('status', '!=', 'pending')
            ->orderBy('created_at')
            ->get();

        $summary = [
            'total_orders'  => $orders->count(),
            'total_revenue' => $orders->sum('total_price'),
            'total_weight'  => $orders->sum('weight_kg'),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', compact('orders', 'summary', 'date'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-' . $dateFormatted . '.pdf');
    }
}
