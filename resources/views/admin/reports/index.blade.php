@extends('layouts.admin')
@section('title', 'Daily Report')
@section('page-title', 'Laporan Harian')
@section('page-subtitle', 'Filter dan ekspor laporan penjualan per hari')

@section('content')

{{-- Date Filter & Download --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
            <input type="date" name="date" value="{{ $dateFormatted }}"
                   class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
            Tampilkan
        </button>
        <a href="{{ route('admin.reports.pdf', ['date' => $dateFormatted]) }}"
           class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Download PDF
        </a>
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-bold text-gray-800">{{ $summary['total_orders'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Order</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Pendapatan</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ number_format($summary['total_weight'], 1) }} Kg</p>
        <p class="text-xs text-gray-500 mt-1">Total Berat</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-lg font-bold text-gray-700">{{ $summary['online_orders'] }} / {{ $summary['offline_orders'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Online / Offline</p>
    </div>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 text-sm">Orders pada {{ $date->format('d F Y') }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="text-left px-5 py-3">Kode</th>
                    <th class="text-left px-5 py-3">Customer</th>
                    <th class="text-left px-5 py-3">Layanan</th>
                    <th class="text-left px-5 py-3">Berat</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-right px-5 py-3">Total</th>
                    <th class="text-right px-5 py-3">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                @php
                    $badges = ['pickup'=>'bg-blue-100 text-blue-700','process'=>'bg-indigo-100 text-indigo-700','finished'=>'bg-green-100 text-green-700','delivered'=>'bg-teal-100 text-teal-700','completed'=>'bg-gray-100 text-gray-600'];
                @endphp
                <tr>
                    <td class="px-5 py-3 font-mono text-primary-600 text-xs font-semibold">{{ $order->order_code }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $order->customer_name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $order->service?->name ?? $order->bundle?->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $order->weight_kg > 0 ? $order->weight_kg . ' Kg' : '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $badges[$order->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-right text-gray-400 text-xs">{{ $order->created_at->format('H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                        Tidak ada order pada tanggal {{ $date->format('d F Y') }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($orders->count() > 0)
            <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                <tr class="font-bold text-gray-800">
                    <td colspan="5" class="px-5 py-3 text-sm">Total ({{ $summary['total_orders'] }} order)</td>
                    <td class="px-5 py-3 text-right text-green-600">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
