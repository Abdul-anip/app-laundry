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
    <div class="dashboard-header">
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
