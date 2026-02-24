<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::withCount('orders')->latest();

        if ($request->filled('type')) {
            $query->where('discount_type', $request->type);
        }
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where(fn($q) => $q->whereNull('expired_at')->orWhereDate('expired_at', '>=', now()));
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->whereNotNull('expired_at')->whereDate('expired_at', '<', now());
            }
        }

        $promos = $query->get();
        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.form', ['promo' => new Promo()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'          => 'required|string|max:50|unique:promos,code',
            'discount_type' => 'required|in:percent,fixed',
            'value'         => 'required|numeric|min:0',
            'expired_at'    => 'nullable|date',
            'is_active'     => 'boolean',
        ]);

        Promo::create([
            'code'          => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'value'         => $request->value,
            'expired_at'    => $request->expired_at,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil ditambahkan!');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.form', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'code'          => 'required|string|max:50|unique:promos,code,' . $promo->id,
            'discount_type' => 'required|in:percent,fixed',
            'value'         => 'required|numeric|min:0',
            'expired_at'    => 'nullable|date',
            'is_active'     => 'boolean',
        ]);

        $promo->update([
            'code'          => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'value'         => $request->value,
            'expired_at'    => $request->expired_at,
            'is_active'     => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diupdate!');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return back()->with('success', 'Promo berhasil dihapus!');
    }
}
