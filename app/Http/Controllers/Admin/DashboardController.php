<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats Overview (from StatsOverview Widget)
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        $monthlyOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $newCustomers = User::where('role', 'customer')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $activeOrders = Order::whereIn('status', ['process', 'pickup'])->count();

        // Revenue Chart — last 30 days (from RevenueChart Widget)
        $revenueData = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartData   = [];
        for ($i = 29; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d M');
            $chartData[]   = $revenueData->has($date) ? (float) $revenueData[$date]->total : 0;
        }

        // Latest Orders (from LatestOrders Widget)
        $latestOrders = Order::with(['service', 'bundle'])
            ->latest()
            ->limit(6)
            ->get();

        // Order status breakdown for donut
        $statusCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.dashboard', compact(
            'monthlyRevenue', 'monthlyOrders', 'newCustomers', 'activeOrders',
            'chartLabels', 'chartData', 'latestOrders', 'statusCounts'
        ));
    }
}
