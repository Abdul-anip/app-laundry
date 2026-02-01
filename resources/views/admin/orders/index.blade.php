@extends('layouts.simple')

@section('title', 'Kelola Pesanan - Admin')

@push('styles')
<style>
    body { 
        background: #F3F4F6;
        margin: 0;
        padding: 0;
    }
    
    .orders-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
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
    
    .orders-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
        background: #F9FAFB;
        border-bottom: 2px solid #E5E7EB;
    }
    
    thead th {
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        color: #ffffffff;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    tbody td {
        padding: 1.25rem 1.25rem;
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
        color: #1F2937;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
    }
    
    .customer-name {
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 0.25rem;
    }
    
    .customer-phone {
        font-size: 0.875rem;
        color: #6B7280;
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
    
    .status-pending {
        background: #FEF3C7;
        color: #92400E;
    }
    
    .status-pickup {
        background: #DBEAFE;
        color: #1E40AF;
    }
    
    .status-process {
        background: #E0E7FF;
        color: #3730A3;
    }
    
    .status-finished {
        background: #D1FAE5;
        color: #065F46;
    }
    
    .status-delivered {
        background: #D1FAE5;
        color: #065F46;
    }
    
    .btn-detail {
        background: #9fbefdff;
        color: #374151;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-block;
    }
    
    .btn-detail:hover {
        background: #E5E7EB;
        color: #1F2937;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #9CA3AF;
    }
    
    .pagination-wrapper {
        margin-top: 2rem;
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .orders-container {
            padding: 1rem;
        }
        .page-header {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }
        .page-title {
            font-size: 1.5rem;
        }
        thead th,
        tbody td {
            padding: 0.875rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@section('content')
<div class="orders-container">
    <div class="page-header">
        <h1 class="page-title">Kelola Pesanan</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>
    </div>

    <div class="orders-card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order Code</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
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
                            <div class="customer-name">{{ $order->customer_name }}</div>
                            <div class="customer-phone">{{ $order->phone }}</div>
                        </td>
                        <td>
                            @if($order->service_id)
                                {{ $order->service->name }} ({{ floatval($order->weight_kg) }}kg)
                            @elseif($order->bundle_id)
                                {{ $order->bundle->name }}
                            @endif
                        </td>
                        <td style="font-weight: 600;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-detail">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            Belum ada pesanan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
