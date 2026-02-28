@extends('layouts.customer')

@section('title', 'Lacak Pesanan - VIP Laundry')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Lacak Pesanan</h1>
    <p class="text-gray-600">Pantau status cucian Anda secara real-time</p>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="p-6">
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('customer.tracking.search') }}" method="POST" class="max-w-2xl">
            @csrf
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="order_code" class="sr-only">Kode Order</label>
                    <input type="text" id="order_code" name="order_code" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 font-mono" 
                           placeholder="Contoh: LDRY-2026-0001" 
                           value="{{ request('order_code') ?? (isset($order) ? $order->order_code : '') }}" 
                           required autofocus>
                </div>
                <button type="submit" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-3 text-center flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                    Lacak
                </button>
            </div>
            <p class="mt-2 text-xs text-gray-500">
                Masukkan kode order yang terdapat pada riwayat pesanan Anda.
            </p>
        </form>
    </div>
</div>

@if(isset($order))
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Details -->
    <div class="lg:col-span-1">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Pesanan</h3>
            
            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Kode Pesanan</p>
                <p class="text-lg font-black font-mono text-gray-900">{{ $order->order_code }}</p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Status Saat Ini</p>
                @php
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu'],
                        'pickup' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Penjemputan'],
                        'process' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Diproses'],
                        'finished' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Selesai Dicuci'],
                        'delivered' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'label' => 'Dikirim'],
                        'completed' => ['bg' => 'bg-gray-800', 'text' => 'text-white', 'label' => 'Selesai'],
                    ];
                    $status = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($order->status)];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                    {{ $status['label'] }}
                </span>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Tanggal Pesan</p>
                <p class="font-medium text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>

            <div class="pt-4 border-t border-gray-100 mt-4">
                <a href="{{ route('customer.orders.show', $order) }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center gap-1 transition-colors">
                    Lihat Detail Lengkap 
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="lg:col-span-2">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                Riwayat Pelacakan
            </h3>
            
            <ol class="relative border-l border-gray-200 ml-3">                  
                @forelse($order->orderTrackings as $index => $track)
                <li class="mb-8 ml-6">
                    <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -left-4 ring-4 ring-white {{ $index === 0 ? 'bg-primary-600' : 'bg-gray-200' }}">
                        @if($index === 0)
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                        @endif
                    </span>
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h4 class="font-bold text-gray-900">{{ ucfirst($track->status) }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $track->description }}</p>
                        </div>
                        <time class="mt-2 sm:mt-0 text-xs font-medium text-gray-400 sm:text-right">
                            {{ $track->created_at->format('d M Y') }}<br>
                            <span class="text-gray-500">{{ $track->created_at->format('H:i') }}</span>
                        </time>
                    </div>
                </li>
                @empty
                <li class="mb-8 ml-6 text-sm text-gray-500 italic">Belum ada pembaruan status.</li>
                @endforelse
            </ol>
        </div>
    </div>
</div>
@endif
@endsection