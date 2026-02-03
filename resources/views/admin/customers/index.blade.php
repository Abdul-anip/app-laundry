@extends('layouts.admin')

@section('title', 'Riwayat Pelanggan - Admin')

@section('content')
    <div class="content-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="welcome-title">👥 Riwayat Pelanggan</h1>
            <p class="welcome-subtitle">Daftar semua pelanggan dan statistik transaksi</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" style="color: #666; text-decoration: none;">Kembali ke Dashboard</a>
    </div>

    <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
        <div class="stat-card">
            <div class="stat-label">Total Pelanggan</div>
            <div class="stat-value">{{ $customers->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Akun Terdaftar</div>
            <div class="stat-value">{{ $customers->where('type', 'registered')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Walk-in Only</div>
            <div class="stat-value">{{ $customers->where('type', 'offline')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Order</div>
            <div class="stat-value">{{ $customers->sum('total_orders') }}</div>
        </div>
    </div>

    @if($customers->count() > 0)
    <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #3b82f6; color: white;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">No</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Nama Pelanggan</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Tipe</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Total Order</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Total Berat (kg)</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Total Transaksi</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Terakhir Order</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $index => $customer)
                <tr style="border-bottom: 1px solid #e5e7eb; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding: 1rem;">{{ $index + 1 }}</td>
                    <td style="padding: 1rem;"><strong>{{ $customer['customer_name'] }}</strong></td>
                    <td style="padding: 1rem;">
                        @if($customer['type'] == 'registered')
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 600; background: #dbeafe; color: #1e40af;">👤 Terdaftar</span>
                        @else
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 600; background: #fef3c7; color: #92400e;">🚶 Walk-in</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">{{ $customer['total_orders'] }} order</td>
                    <td style="padding: 1rem;">{{ number_format($customer['total_weight'], 1) }} kg</td>
                    <td style="padding: 1rem;">Rp {{ number_format($customer['total_revenue'], 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($customer['last_order'])->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="background: #fef3c7; border: 1px solid #f59e0b; padding: 2rem; border-radius: 8px; text-align: center; color: #92400e;">
        ⚠️ Belum ada data pelanggan.
    </div>
    @endif
@endsection
