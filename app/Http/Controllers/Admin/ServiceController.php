<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withCount('orders')->orderBy('name')->get();
        return view('admin.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        Service::create($request->only('name', 'price_per_kg'));
        return redirect()->route('admin.services.index')->with('success', 'Service berhasil ditambahkan!');
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        $service->update($request->only('name', 'price_per_kg'));
        return redirect()->route('admin.services.index')->with('success', 'Service berhasil diupdate!');
    }

    public function destroy(Service $service)
    {
        $orderCount = Order::where('service_id', $service->id)->count();
        if ($orderCount > 0) {
            return back()->with('error', "Tidak bisa hapus service ini karena digunakan di {$orderCount} order.");
        }
        $service->delete();
        return back()->with('success', 'Service berhasil dihapus!');
    }
}
