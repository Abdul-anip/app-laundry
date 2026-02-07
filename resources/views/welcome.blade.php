@extends('layouts.landing')

@section('title', 'VIP Laundry - Your Premium Laundry Service')

@push('styles')
<style>
    /* Hero Section */
    .hero-section {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        padding-top: 4rem;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
    }
    
    .hero-content {
        position: relative;
        z-index: 10;
    }
    
    .scroll-indicator {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translate(-50%, 0); }
        50% { transform: translate(-50%, -10px); }
    }
    
    /* Sections */
    .section {
        padding: 6rem 1rem;
    }
    
    .section-alt {
        background: linear-gradient(180deg, #f9fafb 0%, #ffffff 100%);
    }
    
    /* Cards */
    .feature-card {
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-8px);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left - Text Content -->
            <div class="hero-content text-white">
                <div class="inline-block mb-4 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                    ✨ Premium Laundry Service
                </div>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                    Your Laundry, Our Priority
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100 leading-relaxed">
                    Fast, clean, and fragrant laundry service delivered to your doorstep
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center justify-center gap-2 bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:shadow-2xl transition-all transform hover:scale-105">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        Order Now
                    </a>
                    <a href="{{ route('tracking.index') }}" class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/20 transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                        Track Order
                    </a>
                </div>
            </div>
            
            <!-- Right - Illustration/Stats -->
            <div class="hidden lg:block">
                <div class="relative">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl font-black text-white mb-2">500+</div>
                            <div class="text-blue-100">Happy Customers</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl font-black text-white mb-2">1000+</div>
                            <div class="text-blue-100">Orders Completed</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl font-black text-white mb-2">24/7</div>
                            <div class="text-blue-100">Service Available</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl font-black text-white mb-2">⭐ 4.9</div>
                            <div class="text-blue-100">Average Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="scroll-indicator text-white text-center">
        <div class="text-sm mb-2">Scroll to explore</div>
        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                How It Works
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Simple, fast, and hassle-free laundry service in 3 easy steps
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white text-2xl font-black mb-4">
                    1
                </div>
                <div class="text-6xl mb-4">📱</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Order Online</h3>
                <p class="text-gray-600 leading-relaxed">
                    Choose your service and schedule a pickup time that works for you
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white text-2xl font-black mb-4">
                    2
                </div>
                <div class="text-6xl mb-4">🚗</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">We Pick Up</h3>
                <p class="text-gray-600 leading-relaxed">
                    Our professional team arrives, collects your laundry, and handles it with care
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white text-2xl font-black mb-4">
                    3
                </div>
                <div class="text-6xl mb-4">✨</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Delivered Clean</h3>
                <p class="text-gray-600 leading-relaxed">
                    Clean, fresh, and neatly folded laundry delivered right to your doorstep
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="section section-alt">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Our Services</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Choose the package that fits your needs
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($services as $service)
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2">
                <div class="h-2 bg-gradient-to-r from-blue-600 to-purple-600"></div>
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $service->name }}</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-5xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}
                        </span>
                        <span class="text-xl text-gray-500 ml-2">/kg</span>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed min-h-[3rem]">
                        {{ $service->description ?? 'Premium laundry service with best quality' }}
                    </p>
                    <a href="{{ route('customer.orders.create') }}" class="block w-full text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-xl font-bold hover:shadow-xl transition-all">
                        Choose Service →
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">🔜</div>
                <p class="text-xl text-gray-600">Services coming soon</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                Why Choose Us
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                We provide the best laundry experience
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-6xl mb-4">⚡</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Super Fast</h3>
                <p class="text-gray-600">Lightning-fast service with maximum results, always on time</p>
            </div>
            
            <div class="text-center">
                <div class="text-6xl mb-4">💰</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Affordable Price</h3>
                <p class="text-gray-600">Best prices without compromising on quality</p>
            </div>
            
            <div class="text-center">
                <div class="text-6xl mb-4">👔</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Professional Team</h3>
                <p class="text-gray-600">Experienced, trained, and trustworthy professionals</p>
            </div>
            
            <div class="text-center">
                <div class="text-6xl mb-4">✅</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">100% Satisfaction</h3>
                <p class="text-gray-600">Money-back guarantee if you're not satisfied</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-black mb-6">
            Ready to Experience Premium Laundry?
        </h2>
        <p class="text-xl mb-8 text-blue-100">
            Join thousands of satisfied customers today
        </p>
        <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center gap-2 bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:shadow-2xl transition-all transform hover:scale-105">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
            Start Now
        </a>
    </div>
</section>
@endsection
