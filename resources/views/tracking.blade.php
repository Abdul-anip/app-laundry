@extends('layouts.auth')

@section('title', 'Lacak Pesanan - VIP Laundry')

@push('styles')
<style>
    .tracking-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 2rem 1rem;
    }
    
    .tracking-card {
        background: white;
        border-radius: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 1200px;
        width: 100%;
    }
    
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }
    
    .status-badge {
        animation: pulse-glow 2s ease-in-out infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        }
        50% {
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.8);
        }
    }
    
    @media (max-width: 1023px) {
        .tracking-card {
            flex-direction: column !important;
        }
        .left-panel, .right-panel {
            width: 100% !important;
        }
    }
</style>
@endpush

@section('content')
<div class="tracking-container">
    <div class="tracking-card flex">
        <!-- Left Panel - Illustration -->
        <div class="left-panel w-full lg:w-5/12 bg-gradient-to-br from-blue-500 to-indigo-600 p-12 flex flex-col items-center justify-center relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-20 right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            
            <!-- Logo/Brand -->
            <div class="text-center mb-8 relative z-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-2xl mb-4 float-animation">
                    <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-black text-white mb-2">VIP Laundry</h1>
                <p class="text-blue-100 text-lg">Tracking System</p>
            </div>
            
            <!-- Illustration Placeholder -->
            <div class="relative z-10 mb-8">
                <svg class="w-64 h-64 float-animation" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Washing Machine -->
                    <rect x="50" y="60" width="100" height="120" rx="10" fill="white" opacity="0.9"/>
                    <circle cx="100" cy="110" r="30" fill="#3B82F6" opacity="0.8"/>
                    <circle cx="100" cy="110" r="20" fill="white" opacity="0.5"/>
                    <rect x="60" y="70" width="80" height="8" rx="4" fill="#3B82F6" opacity="0.6"/>
                    <circle cx="75" cy="78" r="2" fill="white"/>
                    <circle cx="125" cy="78" r="2" fill="white"/>
                    <!-- Bubbles -->
                    <circle cx="80" cy="100" r="4" fill="white" opacity="0.7"/>
                    <circle cx="120" cy="95" r="3" fill="white" opacity="0.7"/>
                    <circle cx="90" cy="125" r="3" fill="white" opacity="0.7"/>
                    <circle cx="110" cy="120" r="4" fill="white" opacity="0.7"/>
                </svg>
            </div>
            
            <!-- Info Text -->
            <div class="text-center text-white relative z-10 space-y-2">
                <p class="text-lg font-semibold">Lacak Pesanan Laundry Anda</p>
                <p class="text-blue-100 text-sm">Pantau status cucian secara real-time</p>
            </div>
        </div>
        
        <!-- Right Panel - Form -->
        <div class="right-panel w-full lg:w-7/12 p-8 lg:p-12 flex flex-col justify-center">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mb-2">
                    🔍 Lacak Pesanan Anda
                </h2>
                <p class="text-gray-600">Masukkan kode order untuk melihat status laundry Anda</p>
            </div>
            
            <!-- Error Alert -->
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
            
            <!-- Search Form -->
            <form action="{{ route('tracking.search') }}" method="POST" class="space-y-6 mb-8">
                @csrf
                <div>
                    <label for="order_code" class="block mb-2 text-sm font-bold text-gray-900">
                        Kode Order
                    </label>
                    <input type="text" id="order_code" name="order_code" 
                           class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-lg rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full px-5 py-4 transition-all font-mono" 
                           placeholder="LDRY-2026-0001" 
                           value="{{ request('order_code') ?? (isset($order) ? $order->order_code : '') }}" 
                           required autofocus>
                    <p class="mt-2 text-xs text-gray-500">
                        💡 Kode order bisa ditemukan di riwayat pesanan Anda
                    </p>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                    Lacak Sekarang
                </button>
            </form>
            
            @if(isset($order))
            <!-- Order Result -->
            <div class="space-y-4">
                <!-- Status Card -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kode Pesanan</p>
                            <p class="text-xl font-black text-gray-900">{{ $order->order_code }}</p>
                        </div>
                        @php
                            $statusConfig = [
                                'pending' => ['bg' => 'bg-yellow-400', 'text' => 'text-yellow-900', 'icon' => '⏳'],
                                'pickup' => ['bg' => 'bg-blue-400', 'text' => 'text-blue-900', 'icon' => '🚗'],
                                'process' => ['bg' => 'bg-purple-400', 'text' => 'text-purple-900', 'icon' => '🔄'],
                                'finished' => ['bg' => 'bg-green-400', 'text' => 'text-green-900', 'icon' => '✅'],
                                'delivered' => ['bg' => 'bg-gray-400', 'text' => 'text-gray-900', 'icon' => '📦'],
                            ];
                            $status = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-400', 'text' => 'text-gray-900', 'icon' => '•'];
                        @endphp
                        <span class="status-badge inline-flex items-center gap-2 {{ $status['bg'] }} {{ $status['text'] }} text-sm font-black px-4 py-2 rounded-full">
                            {{ $status['icon'] }} {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Pelanggan</p>
                            <p class="font-bold text-gray-900">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Tanggal</p>
                            <p class="font-bold text-gray-900">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline -->
                <div class="bg-white border-2 border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Riwayat Pesanan
                    </h3>
                    
                    <div class="space-y-3">
                        @forelse($order->orderTrackings as $track)
                        <div class="flex gap-3 items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900">{{ ucfirst($track->status) }}</p>
                                <p class="text-sm text-gray-600">{{ $track->description }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $track->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 italic">Belum ada update</p>
                        @endforelse
                        
                        <!-- Created -->
                        <div class="flex gap-3 items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900">Order Dibuat</p>
                                <p class="text-sm text-gray-600">Pesanan berhasil masuk sistem</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Back Link -->
            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
