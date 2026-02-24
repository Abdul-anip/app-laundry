<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Order;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::withCount('orders')->orderBy('name')->get();
        return view('admin.bundles.index', compact('bundles'));
    }

    public function create()
    {
        return view('admin.bundles.form', ['bundle' => new Bundle()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        Bundle::create($request->only('name', 'price', 'description'));
        return redirect()->route('admin.bundles.index')->with('success', 'Bundle berhasil ditambahkan!');
    }

    public function edit(Bundle $bundle)
    {
        return view('admin.bundles.form', compact('bundle'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $bundle->update($request->only('name', 'price', 'description'));
        return redirect()->route('admin.bundles.index')->with('success', 'Bundle berhasil diupdate!');
    }

    public function destroy(Bundle $bundle)
    {
        $orderCount = Order::where('bundle_id', $bundle->id)->count();
        if ($orderCount > 0) {
            return back()->with('error', "Tidak bisa hapus bundle ini karena digunakan di {$orderCount} order.");
        }
        $bundle->delete();
        return back()->with('success', 'Bundle berhasil dihapus!');
    }
}
