<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Order;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bundles = Bundle::all();
        return view('admin.bundles.index', compact('bundles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bundles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        Bundle::create($request->all());

        return redirect()->route('admin.bundles.index')
            ->with('success', 'Paket berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bundle $bundle)
    {
        return view('admin.bundles.edit', compact('bundle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bundle $bundle)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $bundle->update($request->all());

        return redirect()->route('admin.bundles.index')
            ->with('success', 'Paket berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bundle $bundle)
    {
        // Check if bundle is used in any orders
        $orderCount = Order::where('bundle_id', $bundle->id)->count();
        
        if ($orderCount > 0) {
            return back()->withErrors([
                'error' => "Paket tidak bisa dihapus karena sudah digunakan di {$orderCount} order."
            ]);
        }

        $bundle->delete();

        return redirect()->route('admin.bundles.index')
            ->with('success', 'Paket berhasil dihapus!');
    }
}
