<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — VIP Laundry</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' }
                    }
                }
            }
        }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* sidebar-item: @apply tidak bekerja di Tailwind CDN, gunakan CSS biasa */
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-item:hover { background-color: #1d4ed8; color: #fff; }
        .sidebar-item.active { background-color: #fff; color: #1d4ed8; box-shadow: 0 1px 3px 0 rgb(0 0 0 / .1); }
        .sidebar-item.inactive { color: #bfdbfe; }
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800">

<!-- Sidebar Overlay (mobile) -->
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
     class="fixed inset-0 z-20 bg-black/50 lg:hidden" x-transition.opacity></div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed top-0 left-0 h-screen w-64 bg-gradient-to-b from-primary-800 to-primary-900 z-30 transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col shadow-2xl">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 py-5 border-b border-primary-700">
        <div>
            <p class="text-white font-bold text-sm">VIP Laundry</p>
            <p class="text-primary-300 text-xs">Admin Panel</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

        <p class="text-primary-400 text-xs font-semibold uppercase px-4 mb-2 tracking-wider">Utama</p>

        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
            Beranda
        </a>

        <a href="{{ route('admin.orders.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            Pesanan Masuk
        </a>

        <a href="{{ route('admin.pos') }}"
           class="sidebar-item {{ request()->routeIs('admin.pos') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            Sistem Kasir
        </a>

        <p class="text-primary-400 text-xs font-semibold uppercase px-4 mt-4 mb-2 tracking-wider">Katalog</p>

        <a href="{{ route('admin.services.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.services.*') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Daftar Layanan
        </a>

        <a href="{{ route('admin.bundles.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.bundles.*') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Paket (Bundle)
        </a>

        <a href="{{ route('admin.promos.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.promos.*') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Kode Promo
        </a>

        <p class="text-primary-400 text-xs font-semibold uppercase px-4 mt-4 mb-2 tracking-wider">Pengguna</p>

        <a href="{{ route('admin.customers.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.customers.*') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Daftar Pelanggan
        </a>

        <a href="{{ route('admin.reviews.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.reviews.*') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            Ulasan
        </a>

        <a href="{{ route('admin.staff.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.staff.*') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Staf Admin
        </a>

        <p class="text-primary-400 text-xs font-semibold uppercase px-4 mt-4 mb-2 tracking-wider">Sistem</p>

        <a href="{{ route('admin.reports.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Laporan Harian
        </a>

        <a href="{{ route('admin.settings.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : 'inactive' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Pengaturan
        </a>
    </nav>

    <!-- User Info -->
    <div class="px-3 py-4 border-t border-primary-700">
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary-700/50">
            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-primary-300 text-xs">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="w-full sidebar-item inactive justify-center text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<!-- Main Content -->
<div class="lg:ml-64 min-h-screen flex flex-col">

    <!-- Topbar -->
    <header class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm">
        <div class="flex items-center justify-between px-4 sm:px-6 h-16">
            <div class="flex items-center gap-3">
                <!-- Hamburger -->
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <!-- Page Title -->
                <div>
                    <h1 class="text-lg font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('page-subtitle')
                        <p class="text-xs text-gray-500">@yield('page-subtitle')</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Notification Bell --}}
                @php 
                    $unreadCount = auth()->user()->unreadNotifications->count(); 
                    $initialNotifs = auth()->user()->notifications()->latest()->take(10)->get()->map(function($notif) {
                        // Laravel native notifications can sometimes be cast to array directly depending on model
                        $data = is_string($notif->data) ? json_decode($notif->data, true) : $notif->data;
                        return [
                            'id' => $notif->id,
                            'title' => $data['title'] ?? 'Notifikasi',
                            'body' => $data['body'] ?? '',
                            'url' => $data['url'] ?? route('admin.notifications.read', $notif->id),
                            'read_at' => $notif->read_at,
                            'created_at_human' => $notif->created_at->diffForHumans()
                        ];
                    });
                @endphp
                <div x-data="notificationComponent()" x-init="init()" @click.outside="open = false" class="relative">
                    <button @click="open = !open"
                            class="relative p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        
                        <span x-show="unreadCount > 0" x-cloak class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                            <span x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                        </span>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" x-cloak x-transition
                         class="absolute right-0 top-full mt-2 w-80 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 overflow-hidden">

                        {{-- Header --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-bold text-gray-800">Notifikasi</p>
                            
                            <form x-show="unreadCount > 0" method="POST" action="{{ route('admin.notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="text-xs text-primary-600 hover:text-primary-800 font-medium transition-colors">
                                    Tandai semua dibaca
                                </button>
                            </form>
                        </div>

                        {{-- List --}}
                        <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                            
                            <template x-for="notif in notifications" :key="notif.id">
                                <a :href="notif.url"
                                   :class="notif.read_at ? '' : 'bg-blue-50/40'"
                                   class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                    {{-- Icon dot --}}
                                    <div class="mt-0.5 shrink-0">
                                        <div class="w-2 h-2 rounded-full mt-1.5" :class="notif.read_at ? 'bg-gray-300' : 'bg-primary-500'"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 leading-snug" x-text="notif.title"></p>
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="notif.body"></p>
                                        <p class="text-xs text-gray-400 mt-1" x-text="notif.created_at_human"></p>
                                    </div>
                                </a>
                            </template>
                            
                            <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-sm">Tidak ada notifikasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.pos') }}"
                   class="hidden sm:inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    POS
                </a>
                <a href="{{ url('/') }}" target="_blank"
                   class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors" title="View Site">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="fixed top-4 right-4 z-50 flex flex-col gap-3 pointer-events-none" style="min-width: 320px; max-width: 400px;">
        
        <!-- Alpine Dynamic Toasts -->
        <div x-data="{ toasts: [] }" @notify.window="toasts.push({id: Date.now(), msg: $event.detail.msg}); setTimeout(() => toasts.shift(), 5000)" class="flex flex-col gap-3">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="true" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="pointer-events-auto flex items-center gap-3 bg-white border border-gray-100 shadow-xl rounded-xl p-4 overflow-hidden relative">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900">Info Baru!</p>
                        <p class="text-sm text-gray-500 truncate" x-text="toast.msg"></p>
                    </div>
                </div>
            </template>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        @endif
        @if (session('error') || $errors->any())
            <div x-data="{ show: true }" x-show="show"
                 class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <div class="text-sm">
                    @if (session('error'))
                        <p>{{ session('error') }}</p>
                    @endif
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="flex-1 px-4 sm:px-6 py-4 pb-8">
        @yield('content')
    </main>
</div>

@stack('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationComponent', () => ({
            open: false,
            unreadCount: {{ auth()->user()->unreadNotifications->count() }},
            notifications: @json($initialNotifs),
            
            init() {
                // Polling setiap 10 detik
                setInterval(() => {
                    this.fetchNotifications();
                }, 10000);
            },
            
            async fetchNotifications() {
                try {
                    const response = await fetch('{{ route('admin.notifications.fetch') }}');
                    if (response.ok) {
                        const data = await response.json();
                        
                        if (data.unreadCount > this.unreadCount) { 
                           try {
                               // Suara 'ding' notifikasi yang subtle
                               let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                               audio.volume = 0.5;
                               audio.play();
                           } catch (e) {
                               console.log('Audio autoplay prevented by browser');
                           }
                           
                           // Trigger toast notification
                           window.dispatchEvent(new CustomEvent('notify', {
                               detail: { msg: 'Anda mendapatkan pesanan atau pembaruan baru!' }
                           }));
                        }
                        
                        this.unreadCount = data.unreadCount;
                        this.notifications = data.notifications;
                    }
                } catch (error) {
                    console.error('Failed to fetch notifications', error);
                }
            }
        }));
    });
</script>
</body>
</html>
