<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Dashboard - VIP Laundry')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="h-full">

<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
               <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
        </button>
        <a href="{{ route('customer.dashboard') }}" class="flex ms-2 md:me-24">
          <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-primary-600">VIP Laundry</span>
        </a>
      </div>
      <div class="flex items-center">
          <div class="flex items-center ms-3">
            <!-- Notification Dropdown -->
            <button type="button" data-dropdown-toggle="notification-dropdown" class="relative p-2 mr-3 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100">
              <span class="sr-only">View notifications</span>
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
              @if(auth()->user()->unreadNotifications->count() > 0)
              <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
              @endif
            </button>
            <!-- Dropdown menu -->
            <div id="notification-dropdown" class="z-50 hidden my-4 max-w-sm text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg">
                <div class="block px-4 py-2 text-base font-medium text-center text-gray-700 bg-gray-50">
                    Notifikasi
                </div>
                <div>
                  @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                    <a href="{{ route('customer.orders.show', $notification->data['order_id']) }}" class="flex px-4 py-3 border-b hover:bg-gray-100">
                      <div class="w-full">
                        <div class="text-gray-500 text-sm mb-1.5">
                          <span class="font-semibold text-gray-900">{{ $notification->data['title'] }}</span>
                          {{ $notification->data['message'] }}
                        </div>
                        <div class="text-xs text-primary-600">{{ $notification->created_at->diffForHumans() }}</div>
                      </div>
                    </a>
                  @empty
                    <div class="px-4 py-3 text-sm text-center text-gray-500">
                      Tidak ada notifikasi baru
                    </div>
                  @endforelse
                </div>
            </div>
            
            <!-- User Dropdown -->
            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" data-dropdown-toggle="dropdown-user">
              <span class="sr-only">Open user menu</span>
              <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white font-semibold">
                {{ substr(auth()->user()->name, 0, 1) }}
              </div>
            </button>
            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow" id="dropdown-user">
              <div class="px-4 py-3" role="none">
                <p class="text-sm text-gray-900" role="none">{{ auth()->user()->name }}</p>
                <p class="text-sm font-medium text-gray-900 truncate" role="none">{{ auth()->user()->email }}</p>
              </div>
              <ul class="py-1" role="none">
                <li>
                  <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profile</a>
                </li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Logout</button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
  </div>
</nav>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
      <!-- Loyalty Points Card -->
      <div class="mb-4 p-4 bg-gradient-to-br from-primary-500 to-primary-700 rounded-lg shadow-lg">
        <p class="text-sm text-white/80 mb-1">Loyalty Points</p>
        <p class="text-2xl font-bold text-white">{{ number_format(auth()->user()->points, 0, ',', '.') }}</p>
      </div>
      
      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{ route('customer.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('customer.dashboard') ? 'bg-gray-100' : '' }}">
               <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="{{ route('customer.orders.create') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('customer.orders.create') ? 'bg-gray-100' : '' }}">
               <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Buat Pesanan</span>
               <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-primary-800 bg-primary-100 rounded-full">+</span>
            </a>
         </li>
         <li>
            <a href="{{ route('customer.orders.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('customer.orders.*') && !request()->routeIs('customer.orders.create') ? 'bg-gray-100' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Riwayat Pesanan</span>
            </a>
         </li>
         <li>
            <a href="{{ route('tracking.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('tracking.*') ? 'bg-gray-100' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Lacak Pesanan</span>
            </a>
         </li>
      </ul>
      
      <div class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200">
         <a href="{{ url('/') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
               <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
            </svg>
            <span class="flex-1 ms-3 whitespace-nowrap">Ke Halaman Utama</span>
         </a>
      </div>
   </div>
</aside>

<div class="p-4 sm:ml-64">
   <div class="p-4 mt-14">
      @yield('content')
   </div>
</div>

@stack('scripts')
</body>
</html>
