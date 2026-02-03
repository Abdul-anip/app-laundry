<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - LaundryKu')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; }
        
        body { 
            background: #F3F4F6;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            color: #1F2937;
        }

        /* --- Global Utilities (Safety Net for Non-Tailwind Code) --- */
        
        /* Card */
        .card, .card-lg {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            border: 1px solid #E5E7EB;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            gap: 0.5rem;
        }
        .btn-primary { background: #3B82F6; color: white; }
        .btn-primary:hover { background: #2563EB; }
        
        .btn-success { background: #10B981; color: white; }
        .btn-success:hover { background: #059669; }
        
        .btn-danger { background: #EF4444; color: white; }
        .btn-danger:hover { background: #DC2626; }
        
        .btn-outline { background: white; border: 1px solid #D1D5DB; color: #374151; }
        .btn-outline:hover { background: #F3F4F6; border-color: #9CA3AF; }

        /* Forms */
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151; }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.625rem;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
            background: white;
        }
        .form-input:focus { outline: none; border-color: #3B82F6; ring: 2px solid #BFDBFE; }

        /* Tables */
        .table-container { 
            overflow-x: auto; 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        table { width: 100%; border-collapse: collapse; }
        th { 
            background: #F9FAFB; 
            padding: 0.75rem 1rem; 
            text-align: left; 
            font-size: 0.75rem; 
            font-weight: 600; 
            text-transform: uppercase; 
            color: #6B7280; 
            letter-spacing: 0.05em;
        }
        td { 
            padding: 1rem; 
            border-top: 1px solid #E5E7EB; 
            font-size: 0.875rem;
        }
        tr:hover { background: #F9FAFB; }

        /* Alerts */
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem; font-weight: 500; }
        .alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        .alert-warning { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }

        /* Badge */
        .badge { display: inline-flex; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        
        /* Layout Sidebar Overrides */
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
        .submenu-item {
            display: block;
            padding: 0.75rem 0.75rem;
            color: #6B7280;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            transition: all 0.2s;
            font-size: 0.9rem;
            padding-left: 2.75rem; /* Indent */
        }
        
        .submenu-item:hover {
            background: #F3F4F6;
            color: #1F2937;
            padding-left: 3rem; /* Slight nudge on hover */
        }

        .submenu-item.active {
            color: #1F2937;
            font-weight: 600;
            background: #F3F4F6;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        /* Logout */
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
                border-right: none;
                border-bottom: 1px solid #E5E7EB;
            }
            .sidebar-menu { display: none; } /* Hide menu by default on mobile */
            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h1 class="sidebar-logo">LaundryKu</h1>
            <p class="sidebar-role">Admin Panel</p>
        </div>
        
        <div class="sidebar-menu">
            <!-- Main Menu -->
            <div class="menu-section">
                <div class="menu-section-title">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="menu-icon">🏠</span>
                    Dashboard
                </a>
                <a href="{{ route('admin.pos') }}" class="menu-item {{ request()->routeIs('admin.pos') ? 'active' : '' }}">
                    <span class="menu-icon">🏪</span>
                    Mode Kasir POS
                </a>
            </div>
            
            <!-- Orders -->
            <div class="menu-section">
                <div class="menu-section-title">Pesanan</div>
                <a href="{{ route('admin.orders.index') }}" class="menu-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <span class="menu-icon">📦</span>
                    Kelola Pesanan
                </a>
            </div>
            
            <!-- Products -->
            <div class="menu-section">
                <div class="menu-section-title">Layanan & Produk</div>
                <a href="{{ route('admin.services.index') }}" class="menu-item {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                    <span class="menu-icon">👕</span>
                    Kelola Layanan
                </a>
                <a href="{{ route('admin.bundles.index') }}" class="menu-item {{ request()->routeIs('admin.bundles*') ? 'active' : '' }}">
                    <span class="menu-icon">📦</span>
                    Kelola Paket
                </a>
                <a href="{{ route('admin.promos.index') }}" class="menu-item {{ request()->routeIs('admin.promos*') ? 'active' : '' }}">
                    <span class="menu-icon">🎟️</span>
                    Kelola Promo
                </a>
            </div>
            
            <!-- Management -->
            <div class="menu-section">
                <div class="menu-section-title">Manajemen</div>
                <a href="{{ route('admin.customers.index') }}" class="menu-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                    <span class="menu-icon">👥</span>
                    Data Pelanggan
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="menu-item {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                    <span class="menu-icon">⭐</span>
                    Ulasan Pelanggan
                </a>
                <a href="{{ route('admin.reports.daily') }}" class="menu-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <span class="menu-icon">📊</span>
                    Laporan Harian
                </a>
            </div>

            <!-- Page Management -->
            <div class="menu-section">
                <div class="menu-section-title">Landing Page</div>
                <a href="{{ route('admin.landing.hero.edit') }}" class="submenu-item {{ request()->routeIs('admin.landing.hero*') ? 'active' : '' }}">
                    Hero Section
                </a>
                <a href="{{ route('admin.landing.how-it-works.edit') }}" class="submenu-item {{ request()->routeIs('admin.landing.how-it-works*') ? 'active' : '' }}">
                    How It Works
                </a>
                <a href="{{ route('admin.landing.services.edit') }}" class="submenu-item {{ request()->routeIs('admin.landing.services*') ? 'active' : '' }}">
                    Services Text
                </a>
                <a href="{{ route('admin.landing.why-choose.edit') }}" class="submenu-item {{ request()->routeIs('admin.landing.why-choose*') ? 'active' : '' }}">
                    Why Choose Us
                </a>
                <a href="{{ route('admin.landing.cta.edit') }}" class="submenu-item {{ request()->routeIs('admin.landing.cta*') ? 'active' : '' }}">
                    CTA Section
                </a>
                <a href="{{ route('admin.landing.footer.edit') }}" class="submenu-item {{ request()->routeIs('admin.landing.footer*') ? 'active' : '' }}">
                    Footer
                </a>
            </div>
            
            <!-- Settings -->
            <div class="menu-section">
                <div class="menu-section-title">System</div>
                <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <span class="menu-icon">⚙️</span>
                    Pengaturan
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

    <!-- Main Content Wrapper -->
    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>
