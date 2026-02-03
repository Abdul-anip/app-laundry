@extends('layouts.admin')

@section('title', 'Admin Dashboard - LaundryKu')

@push('styles')
<style>
    /* Content Header */
    .content-header {
        background: white;
        border-radius: 16px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    
    .welcome-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
    }
    
    .welcome-subtitle {
        font-size: 1rem;
        color: #6B7280;
        margin: 0;
    }
    
    /* POS Highlight */
    .pos-card {
        background: linear-gradient(135deg, #10b981 0%, #059669 40%);
        border-radius: 16px;
        padding: 2.5rem;
        text-align: center;
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        margin-bottom: 2rem;
    }
    
    .pos-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }
    
    .pos-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0 0 1.5rem 0;
    }
    
    .btn-pos {
        background: white;
        color: #059669;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .btn-pos:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }
</style>
@endpush

@section('content')
    <div class="content-header">
        <!-- Notification Bell -->
        <div style="position: absolute; top: 2.5rem; right: 2.5rem;">
            <x-dropdown align="right" width="60">
                <x-slot name="trigger">
                    <button class="relative inline-flex items-center p-2 border border-gray-200 rounded-full text-gray-500 bg-white hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                         <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-red-600 ring-2 ring-white"></span>
                        @endif
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 py-2 border-b border-gray-100 font-semibold text-gray-700">
                        Notifikasi Admin
                    </div>
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <x-dropdown-link :href="route('admin.orders.show', $notification->data['order_id'])" class="text-sm border-b border-gray-50">
                            <div class="font-bold text-gray-800">{{ $notification->data['title'] }}</div>
                            <div class="text-xs text-gray-500">{{ $notification->data['message'] }}</div>
                            <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                        </x-dropdown-link>
                    @empty
                        <div class="px-4 py-3 text-sm text-center text-gray-500">
                            Tidak ada notifikasi baru
                        </div>
                    @endforelse
                </x-slot>
            </x-dropdown>
        </div>

        <h1 class="welcome-title">Selamat Datang, Admin!</h1>
        <p class="welcome-subtitle">Kelola laundry Anda dengan mudah</p>
    </div>
    
    <!-- POS Highlight -->
    <div class="pos-card">
        <h2 class="pos-title">Mode Kasir POS</h2>
        <p class="pos-subtitle">Kelola transaksi offline & order langsung</p>
        <a href="{{ route('admin.pos') }}" class="btn-pos">Buka POS</a>
    </div>
@endsection
