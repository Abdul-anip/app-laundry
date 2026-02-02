@extends('layouts.simple')

@section('title', 'Dashboard - LaundryKu')

@push('styles')
<style>
    body { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
    
    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }
    
    .dashboard-header {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .welcome-text {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
    }
    
    .subtitle-text {
        font-size: 1.125rem;
        color: #6B7280;
        margin: 0;
    }
    
    .points-badge {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 1.25rem;
        font-weight: 700;
        margin-top: 1.5rem;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
    
    .points-label {
        font-size: 0.875rem;
        opacity: 0.9;
        margin-right: 0.5rem;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2.5rem;
    }
    
    .action-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        border: 2px solid transparent;
    }
    
    .action-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(102, 126, 234, 0.2);
        border-color: #667eea;
    }
    
    .action-card.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .action-card.primary:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 16px 40px rgba(102, 126, 234, 0.35);
    }
    
    .action-icon {
        width: 60px;
        height: 60px;
        background: rgba(102, 126, 234, 0.1);
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 1.75rem;
        font-weight: 700;
    }
    
    .action-card.primary .action-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .action-title {
        font-size: 1.375rem;
        font-weight: 700;
        margin: 0 0 0.75rem 0;
        color: #1F2937;
    }
    
    .action-card.primary .action-title {
        color: white;
    }
    
    .action-desc {
        font-size: 0.95rem;
        color: #6B7280;
        margin: 0;
        line-height: 1.6;
    }
    
    .action-card.primary .action-desc {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .logout-section {
        text-align: center;
        padding: 2rem 0;
    }
    
    .btn-logout {
        background: white;
        color: #667eea;
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 0.875rem 2rem;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .btn-logout:hover {
        background: #F9FAFB;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
    
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 2rem 1rem;
        }
        .dashboard-header {
            padding: 2rem;
        }
        .welcome-text {
            font-size: 2rem;
        }
        .quick-actions {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header" style="position: relative;">
        <!-- Notification Bell -->
        <div style="position: absolute; top: 2rem; right: 2rem;">
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
                        Notifikasi
                    </div>
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <x-dropdown-link :href="route('customer.orders.show', $notification->data['order_id'])" class="text-sm border-b border-gray-50">
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

        <h1 class="welcome-text">Selamat Datang, {{ auth()->user()->name }}!</h1>
        <p class="subtitle-text">Kelola pesanan laundry Anda dengan mudah</p>
        <div class="points-badge">
            <span class="points-label">Loyalty Points:</span>
            {{ number_format(auth()->user()->points, 0, ',', '.') }}
        </div>
    </div>
    
    <div class="quick-actions">
        <a href="{{ route('customer.orders.create') }}" class="action-card primary">
            <div class="action-icon">+</div>
            <h3 class="action-title">Buat Pesanan</h3>
            <p class="action-desc">Pesan layanan laundry baru dengan mudah</p>
        </a>
        
        <a href="{{ route('customer.orders.index') }}" class="action-card">
            <div class="action-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">≡</div>
            <h3 class="action-title">Riwayat Pesanan</h3>
            <p class="action-desc">Lihat semua pesanan Anda</p>
        </a>
        
        <a href="{{ route('tracking.index') }}" class="action-card">
            <div class="action-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">⌕</div>
            <h3 class="action-title">Lacak Pesanan</h3>
            <p class="action-desc">Cek status pesanan real-time</p>
        </a>
    </div>
    
    <div class="logout-section">
        <form method="POST" action="{{ route('logout') }}" style="display: inline-block;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</div>
@endsection
