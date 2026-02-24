@extends('layouts.admin')
@section('title', 'Detail Customer')
@section('page-title', 'Detail Customer')
@section('page-subtitle', $user->name)

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

    {{-- Customer Info + Stats --}}
    <div class="xl:col-span-1 space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex flex-col items-center text-center mb-4">
                <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center mb-3">
                    <span class="text-2xl font-bold text-primary-600">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <h2 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <p class="text-sm text-gray-500">{{ $user->phone ?? '-' }}</p>
                <p class="text-xs text-gray-400 mt-1">Member sejak {{ $user->created_at->format('d M Y') }}</p>
            </div>
            <div class="flex justify-center">
                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-sm font-semibold">
                    ⭐ {{ $user->points }} Poin
                </span>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 text-sm mb-4">Statistik Order</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Total Order</span>
                    <span class="font-semibold text-gray-800">{{ $stats['total_orders'] }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Total Belanja</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Total Berat</span>
                    <span class="font-semibold text-gray-800">{{ $stats['total_weight'] }} Kg</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-gray-500">Order Terakhir</span>
                    <span class="font-semibold text-gray-800 text-xs">{{ $stats['last_order'] ? $stats['last_order']->format('d M Y') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Order History --}}
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 text-sm">Riwayat Order</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Kode</th>
                        <th class="text-left px-5 py-3">Layanan</th>
                        <th class="text-left px-5 py-3">Status</th>
                        <th class="text-right px-5 py-3">Total</th>
                        <th class="text-right px-5 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                    @php
                        $badges = ['pending'=>'bg-yellow-100 text-yellow-700','pickup'=>'bg-blue-100 text-blue-700','process'=>'bg-indigo-100 text-indigo-700','finished'=>'bg-green-100 text-green-700','delivered'=>'bg-teal-100 text-teal-700','completed'=>'bg-gray-100 text-gray-600'];
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-primary-600 hover:text-primary-800 text-xs font-semibold">{{ $order->order_code }}</a>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $order->service?->name ?? $order->bundle?->name ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $badges[$order->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-5 py-3 text-right font-medium text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right text-gray-500 text-xs">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada order.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($orders->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
