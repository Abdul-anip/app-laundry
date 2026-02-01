@extends('layouts.simple')

@section('title', 'Riwayat Pesanan - LaundryKu')

@push('styles')
<style>
    body { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
    
    .orders-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }
    
    .page-header {
        background: white;
        border-radius: 20px;
        padding: 2.5rem 3rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }
    
    .page-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
    }
    
    .header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .btn-back {
        color: #6B7280;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    
    .btn-back:hover {
        color: #667eea;
    }
    
    .btn-new {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.875rem 1.75rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .btn-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }
    
    /* Desktop Table View */
    .orders-card {
        background: white;
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    thead th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.95rem;
        color: white;
        white-space: nowrap;
    }
    
    tbody td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #F3F4F6;
        color: #374151;
    }
    
    tbody tr:last-child td {
        border-bottom: none;
    }
    
    tbody tr:hover {
        background: #F9FAFB;
    }
    
    .order-code {
        font-weight: 600;
        color: #667eea;
        font-family: monospace;
        font-size: 0.9rem;
    }
    
    .badge {
        display: inline-block;
        padding: 0.4rem 0.9rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }
    
    .status-pending {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .status-pickup {
        background: #E0F2FE;
        color: #0284C7;
    }
    
    .status-process {
        background: #DBEAFE;
        color: #1E40AF;
    }
    
    .status-finished {
        background: #DCFCE7;
        color: #15803D;
    }
    
    .status-delivered {
        background: #D1FAE5;
        color: #047857;
    }
    
    .btn-detail {
        background: #667eea;
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-block;
    }
    
    .btn-detail:hover {
        background: #5568d3;
        transform: scale(1.05);
    }
    
    /* Mobile Card View */
    .mobile-orders {
        display: none;
    }
    
    .order-card-mobile {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .order-card-mobile:hover {
        border-color: #667eea;
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.15);
    }
    
    .order-header-mobile {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .order-code-mobile {
        font-weight: 700;
        color: #667eea;
        font-family: monospace;
        font-size: 0.95rem;
    }
    
    .order-date-mobile {
        font-size: 0.85rem;
        color: #6B7280;
    }
    
    .order-info-mobile {
        margin-bottom: 1rem;
    }
    
    .info-row-mobile {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }
    
    .info-label-mobile {
        color: #6B7280;
        font-weight: 500;
    }
    
    .info-value-mobile {
        color: #1F2937;
        font-weight: 600;
        text-align: right;
    }
    
    .order-footer-mobile {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #F3F4F6;
    }
    
    .btn-detail-mobile {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 600;
        flex: 1;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .btn-detail-mobile:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6B7280;
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }
    
    .empty-text {
        font-size: 1.125rem;
        margin: 0 0 1.5rem 0;
    }
    
    .pagination-wrapper {
        margin-top: 2rem;
        text-align: center;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .orders-container {
            padding: 2rem 1rem;
        }
        
        .page-header {
            padding: 2rem 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }
        
        .page-title {
            font-size: 1.75rem;
        }
        
        .header-actions {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-back {
            order: 2;
            text-align: center;
            padding: 0.75rem;
        }
        
        .btn-new {
            order: 1;
            text-align: center;
        }
        
        /* Hide table, show cards on mobile */
        .orders-card {
            display: none;
        }
        
        .mobile-orders {
            display: block;
        }
    }
</style>
@endpush

@section('content')
<div class="orders-container">
    <div class="page-header">
        <h1 class="page-title">Riwayat Pesanan</h1>
        <div class="header-actions">
            <a href="{{ route('customer.dashboard') }}" class="btn-back">← Kembali</a>
            <a href="{{ route('customer.orders.create') }}" class="btn-new">+ Buat Pesanan Baru</a>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="orders-card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order Code</th>
                        <th>Tanggal</th>
                        <th>Layanan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><span class="order-code">{{ $order->order_code }}</span></td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            @if($order->service_id)
                                {{ $order->service->name }}
                            @elseif($order->bundle_id)
                                {{ $order->bundle->name }}
                            @endif
                        </td>
                        <td style="font-weight: 600;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('customer.orders.show', $order) }}" class="btn-detail">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-icon">□</div>
                            <p class="empty-text">Belum ada pesanan</p>
                            <a href="{{ route('customer.orders.create') }}" class="btn-new" style="display: inline-block;">Buat Pesanan Pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-orders">
        @forelse($orders as $order)
        <div class="order-card-mobile">
            <div class="order-header-mobile">
                <div>
                    <div class="order-code-mobile">{{ $order->order_code }}</div>
                    <div class="order-date-mobile">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>
                <span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
            
            <div class="order-info-mobile">
                <div class="info-row-mobile">
                    <span class="info-label-mobile">Layanan</span>
                    <span class="info-value-mobile">
                        @if($order->service_id)
                            {{ $order->service->name }}
                        @elseif($order->bundle_id)
                            {{ $order->bundle->name }}
                        @endif
                    </span>
                </div>
                <div class="info-row-mobile">
                    <span class="info-label-mobile">Total</span>
                    <span class="info-value-mobile" style="color: #667eea;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <div class="order-footer-mobile">
                <a href="{{ route('customer.orders.show', $order) }}" class="btn-detail-mobile">Lihat Detail</a>
            </div>
        </div>
        @empty
        <div class="empty-state" style="background: white; border-radius: 20px; padding: 3rem 2rem;">
            <div class="empty-icon">□</div>
            <p class="empty-text">Belum ada pesanan</p>
            <a href="{{ route('customer.orders.create') }}" class="btn-new" style="display: inline-block;">Buat Pesanan Pertama</a>
        </div>
        @endforelse
    </div>

    @if($orders->hasPages())
    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
