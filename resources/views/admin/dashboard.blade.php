@extends('layouts.simple')

@section('title', 'Admin Dashboard - LaundryKu')

@push('styles')
<style>
    * { box-sizing: border-box; }
    
    body { 
        background: #F3F4F6;
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
    }
    
    /* Sidebar */
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 280px;
        height: 100vh;
        background: #FFFFFF;
        border-right: 1px solid #E5E7EB;
        padding: 2rem 0;
        overflow-y: auto;
        z-index: 1000;
    }
    
    .sidebar-header {
        padding: 0 1.5rem 2rem 1.5rem;
        border-bottom: 1px solid #E5E7EB;
        margin-bottom: 2rem;
    }
    
    .sidebar-logo {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
    }
    
    .sidebar-role {
        font-size: 0.875rem;
        color: #6B7280;
        margin: 0;
    }
    
    .sidebar-menu {
        padding: 0 1rem;
    }
    
    .menu-section {
        margin-bottom: 2rem;
    }
    
    .menu-section-title {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #9CA3AF;
        padding: 0 0.5rem;
        margin-bottom: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .menu-item {
        display: flex;
        align-items: center;
        padding: 0.875rem 1rem;
        color: #4B5563;
        text-decoration: none;
        border-radius: 10px;
        margin-bottom: 0.25rem;
        transition: all 0.2s;
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    .menu-item:hover {
        background: #F3F4F6;
        color: #1F2937;
    }
    
    .menu-item.active {
        background: #F3F4F6;
        color: #1F2937;
        font-weight: 600;
    }
    
    .menu-icon {
        width: 20px;
        margin-right: 0.875rem;
        text-align: center;
        opacity: 0.7;
    }
    
    /* Submenu */
    .menu-parent {
        cursor: pointer;
    }
    
    .menu-submenu {
        display: none;
        padding-left: 2.75rem;
        margin-top: 0.25rem;
    }
    
    .menu-submenu.open {
        display: block;
    }
    
    .submenu-item {
        display: block;
        padding: 0.75rem 0.75rem;
        color: #6B7280;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 0.25rem;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    
    .submenu-item:hover {
        background: #F3F4F6;
        color: #1F2937;
        padding-left: 1rem;
    }
    
    /* Main Content */
    .main-content {
        margin-left: 280px;
        padding: 2rem;
        min-height: 100vh;
    }
    
    .content-header {
        background: white;
        border-radius: 16px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
    
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6B7280;
        margin: 0 0 0.5rem 0;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1F2937;
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
    
    /* Logout Button */
    .logout-wrapper {
        padding: 1rem 1.5rem;
        border-top: 1px solid #E5E7EB;
        margin-top: auto;
    }
    
    .btn-logout {
        width: 100%;
        background: #F3F4F6;
        color: #4B5563;
        border: none;
        padding: 0.875rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-logout:hover {
        background: #E5E7EB;
        color: #1F2937;
    }
    
    /* Mobile */
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }
        .main-content {
            margin-left: 0;
            padding: 1.5rem;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h1 class="sidebar-logo"></h1>
        <p class="sidebar-role">Admin Panel</p>
    </div>
    
    <div class="sidebar-menu">
        <!-- Main Menu -->
        <div class="menu-section">
            <div class="menu-section-title">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-item active">
                <span class="menu-icon"></span>
                Dashboard
            </a>
            <a href="{{ route('admin.pos') }}" class="menu-item">
                <span class="menu-icon"></span>
                Mode Kasir POS
            </a>
        </div>
        
        <!-- Orders -->
        <div class="menu-section">
            <div class="menu-section-title">Pesanan</div>
            <a href="{{ route('admin.orders.index') }}" class="menu-item">
                <span class="menu-icon"></span>
                Kelola Pesanan
            </a>
        </div>
        
        <!-- Products -->
        <div class="menu-section">
            <div class="menu-section-title">Produk</div>
            <a href="{{ route('admin.services.index') }}" class="menu-item">
                <span class="menu-icon"></span>
                Kelola Layanan
            </a>
            <a href="{{ route('admin.bundles.index') }}" class="menu-item">
                <span class="menu-icon"></span>
                Kelola Paket
            </a>
            <a href="{{ route('admin.promos.index') }}" class="menu-item">
                <span class="menu-icon"></span>
                Kelola Promo
            </a>
        </div>
        
        <!-- Landing Page -->
        <div class="menu-section">
            <div class="menu-section-title">Landing Page</div>
            <a href="{{ route('admin.landing.hero.edit') }}" class="submenu-item">
                Hero Section
            </a>
            <a href="{{ route('admin.landing.how-it-works.edit') }}" class="submenu-item">
                How It Works
            </a>
            <a href="{{ route('admin.landing.services.edit') }}" class="submenu-item">
                Services Text
            </a>
            <a href="{{ route('admin.landing.why-choose.edit') }}" class="submenu-item">
                Why Choose Us
            </a>
            <a href="{{ route('admin.landing.cta.edit') }}" class="submenu-item">
                CTA Section
            </a>
            <a href="{{ route('admin.landing.footer.edit') }}" class="submenu-item">
                Footer
            </a>
        </div>
        
        <!-- Others -->
        <div class="menu-section">
            <div class="menu-section-title">Lainnya</div>
            <a href="{{ route('admin.reviews.index') }}" class="menu-item">
                <span class="menu-icon">⭐</span>
                Lihat Ulasan
            </a>
            <a href="{{ route('admin.reports.daily') }}" class="menu-item">
                <span class="menu-icon">📊</span>
                Laporan Harian
            </a>
            <a href="{{ route('admin.customers.index') }}" class="menu-item">
                <span class="menu-icon">👥</span>
                Riwayat Customer
            </a>
        </div>
    </div>
    
    <div class="logout-wrapper">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <h1 class="welcome-title">Selamat Datang, Admin!</h1>
        <p class="welcome-subtitle">Kelola laundry Anda dengan mudah</p>
    </div>
    
    <!-- POS Highlight -->
    <div class="pos-card">
        <h2 class="pos-title">Mode Kasir POS</h2>
        <p class="pos-subtitle">Kelola transaksi offline & order langsung</p>
        <a href="{{ route('admin.pos') }}" class="btn-pos">Buka POS</a>
    </div>
</div>
@endsection
