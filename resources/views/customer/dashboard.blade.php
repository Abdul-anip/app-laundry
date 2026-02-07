@extends('layouts.customer')

@section('title', 'Dashboard - VIP Laundry')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}!</h1>
    <p class="text-gray-600">Kelola pesanan laundry Anda dengan mudah</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3">
    <!-- Total Orders -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center w-12 h-12 bg-primary-100 rounded-lg">
                    <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders()->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Active Orders -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Sedang Diproses</p>
                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders()->whereIn('status', ['pending', 'process'])->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Completed -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Selesai</p>
                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders()->where('status', 'delivered')->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-6">
    <h2 class="mb-4 text-lg font-semibold text-gray-900">Quick Actions</h2>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <!-- New Order -->
        <a href="{{ route('customer.orders.create') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
            <div class="flex items-center mb-2">
                <div class="flex items-center justify-center w-10 h-10 bg-primary-100 rounded-lg">
                    <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Buat Pesanan</h3>
            <p class="text-sm text-gray-600">Pesan layanan laundry baru</p>
        </a>

        <!-- Order History -->
        <a href="{{ route('customer.orders.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
            <div class="flex items-center mb-2">
                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Riwayat Pesanan</h3>
            <p class="text-sm text-gray-600">Lihat semua pesanan Anda</p>
        </a>

        <!-- Track Order -->
        <a href="{{ route('tracking.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
            <div class="flex items-center mb-2">
                <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Lacak Pesanan</h3>
            <p class="text-sm text-gray-600">Cek status cucian real-time</p>
        </a>
    </div>
</div>

<!-- Recent Orders -->
@if(auth()->user()->orders()->exists())
<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h2>
        <a href="{{ route('customer.orders.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">
            Lihat Semua →
        </a>
    </div>
    
    <div class="bg-white border border-gray-200 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Order Code</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Total</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(auth()->user()->orders()->latest()->take(5)->get() as $order)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap font-mono text-xs">
                            {{ $order->order_code }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 font-semibold">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'process' => 'bg-blue-100 text-blue-800',
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
</div>
@endif

@endsection
