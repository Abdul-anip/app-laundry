@extends('layouts.simple')

@section('title', 'Track Order')

@section('content')
<div class="centered-layout">
    <div class="container-sm">
        <div class="card card-lg">
            <div class="card-header">
                <h1 class="card-title">🔍 Lacak Pesanan</h1>
                <p class="card-subtitle">Masukkan kode order untuk melihat status laundry Anda</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('tracking.search') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="order_code">Kode Order</label>
                    <input type="text" name="order_code" id="order_code" class="form-input" placeholder="LDRY-2026-0001" value="{{ request('order_code') ?? (isset($order) ? $order->order_code : '') }}" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Lacak Sekarang</button>
            </form>

            @if(isset($order))
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--border);">
                    <div style="background: #EFF6FF; padding: 1.5rem; border-radius: 1rem; border: 2px solid var(--primary); margin-bottom: 2rem;">
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--primary); font-size: 1.25rem;">Status: {{ ucfirst($order->status) }}</h3>
                        <p style="margin: 0; color: var(--text-secondary);">Customer: {{ $order->customer_name }}</p>
                    </div>

                    <h3 style="margin-bottom: 1.5rem; color: var(--text-primary);">Riwayat Tracking</h3>
                    
                    <div style="border-left: 3px solid var(--border); padding-left: 1.5rem; position: relative;">
                        @forelse($order->orderTrackings as $track)
                            <div style="position: relative; margin-bottom: 2rem;">
                                <div style="position: absolute; left: -1.95rem; top: 0.25rem; width: 1rem; height: 1rem; background: var(--primary); border-radius: 50%; border: 4px solid white; box-shadow: 0 0 0 1px var(--border);"></div>
                                <div style="font-weight: 700; font-size: 1.1rem; color: var(--text-primary); margin-bottom: 0.25rem;">{{ ucfirst($track->status) }}</div>
                                <div style="color: var(--text-secondary); margin-bottom: 0.25rem;">{{ $track->description }}</div>
                                <div style="font-size: 0.875rem; color: #9CA3AF;">{{ $track->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        @empty
                            <p style="color: var(--text-secondary);">Belum ada riwayat tracking.</p>
                        @endforelse
                        
                        <div style="position: relative; margin-bottom: 1rem;">
                            <div style="position: absolute; left: -1.95rem; top: 0.25rem; width: 1rem; height: 1rem; background: #10B981; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 0 1px var(--border);"></div>
                            <div style="font-weight: 700; font-size: 1.1rem; color: var(--text-primary); margin-bottom: 0.25rem;">Order Dibuat</div>
                            <div style="color: var(--text-secondary); margin-bottom: 0.25rem;">Pesanan berhasil dibuat di sistem</div>
                            <div style="font-size: 0.875rem; color: #9CA3AF;">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="text-center mt-3">
            <a href="{{ url('/') }}" style="color: var(--text-secondary); text-decoration: none; font-weight: 500;">
                ← Kembali ke Home
            </a>
        </div>
    </div>
</div>
@endsection
