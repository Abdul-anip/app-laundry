@extends('layouts.simple')

@section('title', 'Detail Pesanan ' . $order->order_code . ' - Admin')

@push('styles')
<style>
    body { 
        background: #F3F4F6;
        margin: 0;
        padding: 0;
    }
    
    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .btn-back {
        color: #6B7280;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    
    .btn-back:hover {
        color: #1F2937;
    }
    
    .btn-print {
        background: #1F2937;
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    
    .btn-print:hover {
        background: #374151;
    }
    
    .grid-2 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    
    .card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 1.5rem 0;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .order-code {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1F2937;
        font-family: 'Courier New', monospace;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1F2937;
        margin: 1.5rem 0 1rem 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.875rem;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F9FAFB;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: #6B7280;
        font-size: 0.9375rem;
    }
    
    .detail-value {
        color: #1F2937;
        font-weight: 500;
        text-align: right;
    }
    
    .total-row {
        padding: 1rem 0;
        border-top: 2px solid #E5E7EB;
        margin-top: 0.5rem;
    }
    
    .total-row .detail-label,
    .total-row .detail-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
    }
    
    .badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending { background: #FEF3C7; color: #92400E; }
    .status-pickup { background: #DBEAFE; color: #1E40AF; }
    .status-process { background: #E0E7FF; color: #3730A3; }
    .status-finished { background: #D1FAE5; color: #065F46; }
    .status-delivered { background: #D1FAE5; color: #065F46; }
    
    /* Map Link */
    .map-link {
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        color: #374151;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s;
        margin-top: 0.5rem;
    }
    
    .map-link:hover {
        background: #E5E7EB;
        color: #1F2937;
    }
    
    /* Tracking */
    .tracking-item {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-left: 2px solid #E5E7EB;
    }
    
    .tracking-item:last-child {
        border-left-color: transparent;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .tracking-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 0;
        width: 10px;
        height: 10px;
        background: #1F2937;
        border-radius: 50%;
    }
    
    .tracking-status {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }
    
    .tracking-desc {
        color: #6B7280;
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }
    
    .tracking-time {
        color: #9CA3AF;
        font-size: 0.8125rem;
    }
    
    /* Update Form */
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.9375rem;
    }
    
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 0.9375rem;
        transition: border-color 0.2s;
        background: white;
    }
    
    .form-select:focus {
        outline: none;
        border-color: #1F2937;
    }
    
    .btn-update {
        width: 100%;
        background: #1F2937;
        color: white;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-update:hover {
        background: #374151;
    }
    
    .form-hint {
        display: block;
        margin-top: 0.75rem;
        color: #6B7280;
        font-size: 0.8125rem;
    }
    
    .alert-success {
        background: #D1FAE5;
        border: 1px solid #6EE7B7;
        color: #065F46;
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        font-weight: 500;
    }
    
    @media (max-width: 968px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
        .detail-container {
            padding: 1.5rem;
        }
        .card {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="detail-container">
    <div class="page-header">
        <a href="{{ route('admin.orders.index') }}" class="btn-back">← Kembali ke Daftar Pesanan</a>
        <a href="{{ route('admin.orders.print', $order) }}" class="btn-print" target="_blank">Cetak Struk PDF</a>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="grid-2">
        <!-- Order Details -->
        <div>
            <div class="card">
                <div class="order-header">
                    <h1 class="order-code">#{{ $order->order_code }}</h1>
                    <span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </div>

                <h3 class="section-title">Informasi Pelanggan</h3>
                <div class="detail-row">
                    <span class="detail-label">Nama</span>
                    <span class="detail-value">{{ $order->customer_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Telepon</span>
                    <span class="detail-value">{{ $order->phone }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Alamat</span>
                    <span class="detail-value" style="max-width: 60%; text-align: right;">{{ $order->address }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Koordinat GPS</span>
                    <span class="detail-value" style="font-family: 'Courier New', monospace; font-size: 0.875rem;">
                        @if($order->latitude && $order->longitude)
                            {{ number_format($order->latitude, 6) }}, {{ number_format($order->longitude, 6) }}
                        @else
                            <span style="color: #9CA3AF; font-family: inherit;">Tidak tersedia</span>
                        @endif
                    </span>
                </div>
                @if($order->latitude && $order->longitude)
                <div class="detail-row">
                    <span class="detail-label">Lihat Peta</span>
                    <span class="detail-value">
                        <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" 
                           target="_blank" 
                           class="map-link">
                            📍 Buka di Google Maps
                        </a>
                    </span>
                </div>
                @endif

                <h3 class="section-title">Informasi Layanan</h3>
                <div class="detail-row">
                    <span class="detail-label">Jenis Pesanan</span>
                    <span class="detail-value">
                        @if($order->service_id)
                            {{ $order->service->name }} ({{ floatval($order->weight_kg) }} kg)
                        @elseif($order->bundle_id)
                            {{ $order->bundle->name }}
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tipe Kain</span>
                    <span class="detail-value">{{ $order->fabric_type ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal Jemput</span>
                    <span class="detail-value">{{ $order->pickup_date->format('d M Y') }} - {{ $order->pickup_time }}</span>
                </div>

                <h3 class="section-title">Rincian Biaya</h3>
                <div class="detail-row">
                    <span class="detail-label">Subtotal</span>
                    <span class="detail-value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Pickup Fee ({{ floatval($order->distance_km) }} km)</span>
                    <span class="detail-value">Rp {{ number_format($order->pickup_fee, 0, ',', '.') }}</span>
                </div>
                @if($order->discount > 0)
                <div class="detail-row">
                    <span class="detail-label" style="color: #059669;">Diskon</span>
                    <span class="detail-value" style="color: #059669;">- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="detail-row total-row">
                    <span class="detail-label">Total</span>
                    <span class="detail-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status Pembayaran</span>
                    <span class="detail-value" style="color: {{ $order->payment_status == 'paid' ? '#059669' : '#D97706' }}; font-weight: 600;">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title">Riwayat Status</h3>
                @forelse($order->orderTrackings as $track)
                    <div class="tracking-item">
                        <div class="tracking-status">{{ ucfirst($track->status) }}</div>
                        <div class="tracking-desc">{{ $track->description }}</div>
                        <div class="tracking-time">{{ $track->created_at->format('d M Y H:i') }}</div>
                    </div>
                @empty
                    <p style="color: #9CA3AF; text-align: center; padding: 2rem 0;">Belum ada riwayat update</p>
                @endforelse
            </div>
        </div>

        <!-- Update Status -->
        <div>
            <div class="card">
                <h3 class="card-title">Update Status</h3>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label" for="status">Pilih Status Terbaru</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="pickup" {{ $order->status == 'pickup' ? 'selected' : '' }}>Pickup (Dijemput)</option>
                            <option value="process" {{ $order->status == 'process' ? 'selected' : '' }}>Process (Dicuci)</option>
                            <option value="finished" {{ $order->status == 'finished' ? 'selected' : '' }}>Finished (Selesai)</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered (Diantar & Bayar)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-update">Update Status</button>
                    
                    <small class="form-hint">
                        * Status 'Delivered' otomatis mengubah status pembayaran menjadi Paid
                    </small>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
