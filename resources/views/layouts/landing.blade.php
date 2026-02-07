<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VIP Laundry')</title>
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="fixed w-full top-0 z-50 bg-white/90 backdrop-blur-lg border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    
                    <span class="text-xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">VIP Laundry</span>
                </a>
                
                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#services" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Services</a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">How It Works</a>
                    <a href="{{ route('tracking.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Track Order</a>
                    @auth
                        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:shadow-lg transition-all">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:shadow-lg transition-all">
                            Get Started
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-lg hover:bg-gray-100" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200 bg-white">
            <div class="px-4 py-3 space-y-2">
                <a href="#services" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">Services</a>
                <a href="#how-it-works" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">How It Works</a>
                <a href="{{ route('tracking.index') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">Track Order</a>
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="block px-3 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center font-medium">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center font-medium">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>
    
    @yield('content')
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        
                        <span class="text-xl font-black text-white">VIP Laundry</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Your premium laundry service partner. Fast, clean, and fragrant - every single time.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-white font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('customer.orders.create') }}" class="hover:text-white transition-colors">Order Laundry</a></li>
                        <li><a href="{{ route('tracking.index') }}" class="hover:text-white transition-colors">Track Order</a></li>
                        @auth
                            <li><a href="{{ route('customer.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Register</a></li>
                        @endauth
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-white font-bold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <span>📧</span>
                            <span>support@viplaundry.com</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>📱</span>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>📍</span>
                            <span>Jakarta, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} VIP Laundry. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
    
    @stack('scripts')
</body>
</html>
