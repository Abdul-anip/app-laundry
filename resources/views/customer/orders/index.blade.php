@extends('layouts.customer')

@section('title', 'Riwayat Pesanan - VIP Laundry')

@section('content')
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Pesanan</h1>
            <p class="text-gray-600 mt-1">Lihat semua pesanan laundry Anda</p>
        </div>
        <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
            </svg>
            Buat Pesanan Baru
        </a>
    </div>
</div>

@if($orders->count() > 0)

<!-- Mobile Card View (Hidden on Desktop) -->
<div class="md:hidden space-y-4">
    @foreach($orders as $order)
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-primary-50 to-primary-100 px-4 py-3 border-b border-primary-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs text-gray-600 mb-1">Kode Pesanan</p>
                    <p class="font-mono text-sm font-bold text-gray-900">{{ $order->order_code }}</p>
                </div>
                @php
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => '⏳', 'label' => 'Menunggu'],
                        'pickup' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => '🚗', 'label' => 'Penjemputan'],
                        'process' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'icon' => '🧺', 'label' => 'Diproses'],
                        'finished' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '✅', 'label' => 'Selesai Dicuci'],
                        'delivered' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '📦', 'label' => 'Dikirim'],
                        'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'icon' => '🎉', 'label' => 'Selesai'],
                    ];
                    $config = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '•', 'label' => $order->status];
                @endphp
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                    <span class="mr-1">{{ $config['icon'] }}</span>
                    {{ $config['label'] }}
                </span>
            </div>
        </div>

        <!-- Card Body -->
        <div class="px-4 py-4 space-y-3">
            <!-- Date -->
            <div class="flex items-center text-sm">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</span>
            </div>

            <!-- Service -->
            <div class="flex items-center text-sm">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span class="text-gray-900 font-medium">
                    @if($order->service_id)
                        {{ $order->service->name }}
                    @elseif($order->bundle_id)
                        {{ $order->bundle->name }}
                    @endif
                </span>
            </div>

            <!-- Price -->
            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                <span class="text-sm text-gray-600">Total Harga</span>
                <span class="text-lg font-bold text-primary-600">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
            <a href="{{ route('customer.orders.show', $order) }}" class="block w-full text-center px-4 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 active:bg-primary-800 transition-colors">
                Lihat Detail
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- Desktop Table View (Hidden on Mobile) -->
<div class="hidden md:block bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">Kode Pesanan</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Tanggal</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Layanan</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Total</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr class="bg-white hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900">
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $order->order_code }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        {{ $order->created_at->format('d M Y') }}
                        <span class="text-xs text-gray-500 block">{{ $order->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-900">
                        @if($order->service_id)
                            {{ $order->service->name }}
                        @elseif($order->bundle_id)
                            <span class="inline-flex items-center">
                                {{ $order->bundle->name }}
                                <span class="ml-1.5 px-1.5 py-0.5 text-xs bg-amber-100 text-amber-800 rounded">Bundle</span>
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusConfig = [
                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => '⏳', 'label' => 'Menunggu'],
                                'pickup' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => '🚗', 'label' => 'Penjemputan'],
                                'process' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'icon' => '🧺', 'label' => 'Diproses'],
                                'finished' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '✅', 'label' => 'Selesai Dicuci'],
                                'delivered' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '📦', 'label' => 'Dikirim'],
                                'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'icon' => '🎉', 'label' => 'Selesai'],
                            ];
                            $config = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => '•', 'label' => ucfirst($order->status)];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <span class="mr-1">{{ $config['icon'] }}</span>
                            {{ $config['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('customer.orders.show', $order) }}" class="inline-flex items-center font-medium text-primary-600 hover:text-primary-700 hover:underline transition-colors">
                            Detail
                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
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
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-12">
    <div class="text-center max-w-sm mx-auto">
        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gray-100 mb-4">
            <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">Belum ada pesanan</h3>
        <p class="text-sm text-gray-500 mb-6">Mulai dengan membuat pesanan laundry pertama Anda.</p>
        <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
            </svg>
            Buat Pesanan Pertama
        </a>
    </div>
</div>
@endif

@endsection
