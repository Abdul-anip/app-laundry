<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.promos.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promos,code|max:50',
            'discount_type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'expired_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        Promo::create([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'value' => $request->value,
            'expired_at' => $request->expired_at,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promos,code,' . $promo->id,
            'discount_type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'expired_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $promo->update([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'value' => $request->value,
            'expired_at' => $request->expired_at,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo deleted successfully.');
    }
}
