<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display customer history list
     */
    public function index()
    {
        // Get registered customers (with user accounts)
        $registeredCustomers = Order::whereNotNull('customer_user_id')
            ->select('customer_user_id', 'customer_name')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(weight_kg) as total_weight')
            ->selectRaw('SUM(total_price) as total_revenue')
            ->selectRaw('MAX(created_at) as last_order')
            ->groupBy('customer_user_id', 'customer_name')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'registered',
                    'customer_user_id' => $item->customer_user_id,
                    'customer_name' => $item->customer_name,
                    'total_orders' => $item->total_orders,
                    'total_weight' => floatval($item->total_weight),
                    'total_revenue' => floatval($item->total_revenue),
                    'last_order' => $item->last_order,
                ];
            });

        // Get offline customers (walk-in, no user account)
        $offlineCustomers = Order::whereNull('customer_user_id')
            ->select('customer_name')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(weight_kg) as total_weight')
            ->selectRaw('SUM(total_price) as total_revenue')
            ->selectRaw('MAX(created_at) as last_order')
            ->groupBy('customer_name')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'offline',
                    'customer_user_id' => null,
                    'customer_name' => $item->customer_name,
                    'total_orders' => $item->total_orders,
                    'total_weight' => floatval($item->total_weight),
                    'total_revenue' => floatval($item->total_revenue),
                    'last_order' => $item->last_order,
                ];
            });

        // Merge and sort by last order
        $customers = $registeredCustomers->concat($offlineCustomers)
            ->sortByDesc('last_order')
            ->values();

        return view('admin.customers.index', compact('customers'));
    }
}
