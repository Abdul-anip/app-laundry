<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total_price')
            ->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->orWhere('phone', 'like', "%$s%"));
        }
        if ($request->filled('filter')) {
            if ($request->filter === 'has_orders') {
                $query->has('orders');
            } elseif ($request->filter === 'no_orders') {
                $query->doesntHave('orders');
            }
        }

        $customers = $query->paginate(20)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'customer', 404);

        $stats = [
            'total_orders'  => $user->orders()->count(),
            'total_spent'   => $user->orders()->sum('total_price'),
            'total_weight'  => $user->orders()->sum('weight_kg'),
            'last_order'    => $user->orders()->latest()->first()?->created_at,
        ];

        $orders = $user->orders()->with(['service','bundle'])->latest()->paginate(10);

        return view('admin.customers.show', compact('user', 'stats', 'orders'));
    }
}
