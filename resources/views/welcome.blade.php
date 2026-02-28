@extends('layouts.landing')

@section('title', 'VIP Laundry - Your Premium Laundry Service')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left - Text Content -->
            <div class="hero-content text-white">
                <div class="inline-block mb-4 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                    ✨ Layanan Laundry Premium
                </div>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                    Pakaian Bersih Anda, Prioritas Kami
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100 leading-relaxed">
                    Layanan laundry cepat, bersih, dan harum yang langsung diantar ke depan pintu Anda
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center justify-center gap-2 bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:shadow-2xl transition-all transform hover:scale-105">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        Pesan Sekarang
                    </a>
                    <a href="{{ route('customer.tracking.index') }}" class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/20 transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                        Lacak Pesanan
                    </a>
                </div>
            </div>
            
            
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="scroll-indicator text-white text-center">
        <div class="text-sm mb-2">Gulir ke bawah</div>
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
                Cara Kerja Kami
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Cucian bersih tanpa ribet hanya dalam 3 langkah mudah
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white text-2xl font-black mb-4">
                    1
                </div>
                <div class="text-6xl mb-4">📱</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Pesan Online</h3>
                <p class="text-gray-600 leading-relaxed">
                    Pilih layanan dan jadwalkan waktu penjemputan yang pas untuk Anda
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white text-2xl font-black mb-4">
                    2
                </div>
                <div class="text-6xl mb-4">🚗</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Kami Jemput</h3>
                <p class="text-gray-600 leading-relaxed">
                    Tim profesional kami akan datang mengambil dan menangani cucian Anda dengan hati-hati
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white text-2xl font-black mb-4">
                    3
                </div>
                <div class="text-6xl mb-4">✨</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Diantar Bersih</h3>
                <p class="text-gray-600 leading-relaxed">
                    Cucian yang sudah bersih, segar, dan terlipat rapi langsung diantar ke rumah Anda
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="section section-alt">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Layanan Kami</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Pilih paket yang paling sesuai dengan kebutuhan Anda
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($services as $service)
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2">
                <div class="h-2 bg-blue-600"></div>
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $service->name }}</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-5xl font-black text-blue-600">
                            Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}
                        </span>
                        <span class="text-xl text-gray-500 ml-2">/kg</span>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed min-h-[3rem]">
                        {{ $service->description ?? 'Layanan laundry premium dengan kualitas terbaik' }}
                    </p>
                    <a href="{{ route('customer.orders.create') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold hover:shadow-xl transition-all">
                        Pilih Layanan →
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">🔜</div>
                <p class="text-xl text-gray-600">Layanan akan segera hadir</p>
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
                Mengapa Memilih Kami?
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Kami memberikan pengalaman laundry terbaik untuk Anda
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-6xl mb-4">⚡</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Super Cepat</h3>
                <p class="text-gray-600">Layanan kilat dengan hasil maksimal, selalu tepat waktu</p>
            </div>
            
            <div class="text-center">
                <div class="text-6xl mb-4">💰</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Harga Terjangkau</h3>
                <p class="text-gray-600">Harga terbaik tanpa mengorbankan kualitas cucian</p>
            </div>
            
            <div class="text-center">
                <div class="text-6xl mb-4">👔</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tim Profesional</h3>
                <p class="text-gray-600">Dikerjakan oleh profesional yang berpengalaman dan terpercaya</p>
            </div>
            
            <div class="text-center">
                <div class="text-6xl mb-4">✅</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">100% Kepuasan</h3>
                <p class="text-gray-600">Garansi kepuasan atau uang kembali jika Anda tidak puas</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section bg-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-black mb-6">
            Siap Merasakan Layanan Laundry Premium?
        </h2>
        <p class="text-xl mb-8 text-blue-100">
            Bergabunglah dengan ribuan pelanggan yang puas hari ini
        </p>
        <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center gap-2 bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:shadow-2xl transition-all transform hover:scale-105">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
            Mulai Sekarang
        </a>
    </div>
</section>
@endsection
