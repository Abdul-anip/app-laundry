@extends('layouts.customer')

@section('title', 'Riwayat Pesanan - VIP Laundry')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Pesanan</h1>
            <p class="text-gray-600">Lihat semua pesanan laundry Anda</p>
        </div>
        <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
            </svg>
            Buat Pesanan Baru
        </a>
    </div>
</div>

@if($orders->count() > 0)
<!-- Orders Table (Desktop) -->
<div class="bg-white border border-gray-200 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Order Code</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Layanan</th>
                    <th scope="col" class="px-6 py-3">Total</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <span class="font-mono text-xs">{{ $order->order_code }}</span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $order->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($order->service_id)
                            {{ $order->service->name }}
                        @elseif($order->bundle_id)
                            {{ $order->bundle->name }}
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'pickup' => 'bg-blue-100 text-blue-800',
                                'process' => 'bg-indigo-100 text-indigo-800',
                                'finished' => 'bg-green-100 text-green-800',
                                'delivered' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="px-2.5 py-0.5 rounded text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('customer.orders.show', $order) }}" class="font-medium text-primary-600 hover:underline">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($orders->hasPages())
<div class="mt-6">
    {{ $orders->links() }}
</div>
@endif

@else
<!-- Empty State -->
<div class="bg-white border border-gray-200 rounded-lg shadow p-12">
    <div class="text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum ada pesanan</h3>
        <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat pesanan laundry pertama Anda.</p>
        <div class="mt-6">
            <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                </svg>
                Buat Pesanan Pertama
            </a>
        </div>
    </div>
</div>
@endif

@endsection
