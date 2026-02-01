@extends('layouts.simple')

@section('title', 'LaundryKu - Layanan Laundry Online Terpercaya')

@push('styles')
<style>
    body { margin: 0; padding: 0; }
    
    /* Hero Section - Saweria Style */
    .hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    /* Subtle pattern overlay */
    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.5;
    }
    
    .hero-card {
        background: white;
        border-radius: 24px;
        padding: 3.5rem 3rem;
        max-width: 580px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 1;
        animation: fadeInUp 0.6s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .hero-icon {
        font-size: 5rem;
        text-align: center;
        margin-bottom: 1.5rem;
        line-height: 1;
    }
    
    .hero-title {
        font-size: 2.75rem;
        font-weight: 700;
        text-align: center;
        margin: 0 0 1rem 0;
        color: #1F2937;
        line-height: 1.2;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        text-align: center;
        color: #6B7280;
        margin: 0 0 2.5rem 0;
        line-height: 1.6;
    }
    
    .hero-cta {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-hero {
        padding: 1.125rem 2rem;
        font-size: 1.125rem;
        font-weight: 600;
        border-radius: 16px;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: block;
    }
    
    .btn-hero-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-hero-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }
    
    .btn-hero-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #E5E7EB;
    }
    
    .btn-hero-secondary:hover {
        border-color: #667eea;
        background: #F9FAFB;
    }
    
    /* Section Styles */
    .section {
        padding: 6rem 2rem;
        background: white;
    }
    
    .section-alt {
        background: linear-gradient(180deg, #F9FAFB 0%, #FFFFFF 100%);
    }
    
    .section-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 1rem 0;
        color: #1F2937;
    }
    
    .section-subtitle {
        text-align: center;
        font-size: 1.25rem;
        color: #6B7280;
        margin: 0 0 4rem 0;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Steps - Saweria Style */
    .steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2.5rem;
        max-width: 1100px;
        margin: 0 auto;
    }
    
    .step-card {
        background: white;
        padding: 3rem 2.5rem;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #F3F4F6;
    }
    
    .step-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(102, 126, 234, 0.15);
    }
    
    .step-icon {
        font-size: 4.5rem;
        margin-bottom: 1.5rem;
        line-height: 1;
    }
    
    .step-number {
        display: inline-block;
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        font-weight: 700;
        line-height: 3rem;
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
    }
    
    .step-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 1rem 0;
        color: #1F2937;
    }
    
    .step-desc {
        color: #6B7280;
        margin: 0;
        line-height: 1.7;
        font-size: 1rem;
    }
    
    /* Services - Saweria Style */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2.5rem;
        max-width: 1100px;
        margin: 0 auto;
    }
    
    .service-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .service-card:hover::before {
        transform: scaleX(1);
    }
    
    .service-card:hover {
        border-color: #667eea;
        box-shadow: 0 12px 32px rgba(102, 126, 234, 0.2);
        transform: translateY(-4px);
    }
    
    .service-name {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 1rem 0;
        color: #1F2937;
    }
    
    .service-price {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 1.5rem 0;
    }
    
    .service-desc {
        color: #6B7280;
        margin: 0 0 2rem 0;
        line-height: 1.7;
        min-height: 3rem;
    }
    
    /* Features */
    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 2.5rem;
        max-width: 1100px;
        margin: 0 auto;
    }
    
    .feature {
        text-align: center;
        padding: 2rem 1.5rem;
    }
    
    .feature-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        line-height: 1;
    }
    
    .feature-title {
        font-size: 1.375rem;
        font-weight: 700;
        margin: 0 0 0.75rem 0;
        color: #1F2937;
    }
    
    .feature-desc {
        color: #6B7280;
        margin: 0;
        line-height: 1.7;
    }
    
    /* CTA Section - Saweria Style */
    .cta-section {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        padding: 6rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.5;
    }
    
    .cta-content {
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .cta-section h2 {
        font-size: 3rem;
        font-weight: 700;
        margin: 0 0 1.5rem 0;
        color: white;
    }
    
    .cta-section p {
        font-size: 1.375rem;
        margin: 0 0 2.5rem 0;
        color: rgba(255, 255, 255, 0.95);
        line-height: 1.6;
    }
    
    .btn-cta {
        padding: 1.25rem 3rem;
        font-size: 1.25rem;
        font-weight: 600;
        border-radius: 16px;
        background: white;
        color: #059669;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }
    
    .btn-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
    }
    
    /* Footer - Minimal Saweria Style */
    .footer {
        background: #111827;
        color: rgba(255, 255, 255, 0.8);
        padding: 4rem 2rem 2rem;
    }
    
    .footer-content {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 3rem;
        margin-bottom: 3rem;
    }
    
    .footer-section h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0 0 1.5rem 0;
        color: white;
    }
    
    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-links li {
        margin-bottom: 0.75rem;
    }
    
    .footer-links a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: color 0.2s;
        font-size: 1rem;
    }
    
    .footer-links a:hover {
        color: white;
    }
    
    .footer-bottom {
        text-align: center;
        padding-top: 2.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.95rem;
    }
    
    /* Button Style Override */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 14px;
        padding: 1rem 2rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }
    
    @media (max-width: 768px) {
        .hero-card {
            padding: 2.5rem 2rem;
        }
        .hero-title {
            font-size: 2.25rem;
        }
        .hero-subtitle {
            font-size: 1.125rem;
        }
        .section-title {
            font-size: 2rem;
        }
        .section {
            padding: 4rem 1.5rem;
        }
        .cta-section h2 {
            font-size: 2.25rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section - Saweria Style -->
<section class="hero">
    <div class="hero-card">
        <h1 class="hero-title">{{ $settings->hero_title }}</h1>
        <p class="hero-subtitle">{{ $settings->hero_subtitle }}</p>
        
        <div class="hero-cta">
            <a href="{{ route('customer.orders.create') }}" class="btn-hero btn-hero-primary">
                {{ $settings->hero_cta_primary }}
            </a>
            <a href="{{ route('tracking.index') }}" class="btn-hero btn-hero-secondary">
                {{ $settings->hero_cta_secondary }}
            </a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="section">
    <h2 class="section-title">{{ $settings->how_it_works_title }}</h2>
    <p class="section-subtitle">{{ $settings->how_it_works_subtitle }}</p>
    
    <div class="steps">
        <div class="step-card">
            <div class="step-icon">📱</div>
            <div class="step-number">1</div>
            <h3 class="step-title">Pesan Online</h3>
            <p class="step-desc">Pilih layanan favorit dan tentukan waktu pickup yang cocok untuk kamu</p>
        </div>
        
        <div class="step-card">
            <div class="step-icon">🚗</div>
            <div class="step-number">2</div>
            <h3 class="step-title">Kami Jemput</h3>
            <p class="step-desc">Tim profesional kami datang, ambil cucian, dan kerjakan dengan sempurna</p>
        </div>
        
        <div class="step-card">
            <div class="step-icon">✨</div>
            <div class="step-number">3</div>
            <h3 class="step-title">Antar Bersih</h3>
            <p class="step-desc">Cucian bersih, wangi, rapi diantar langsung ke tempatmu</p>
        </div>
    </div>
</section>

<!-- Services -->
<section class="section section-alt">
    <h2 class="section-title">💎 Layanan Kami</h2>
    <p class="section-subtitle">Pilih paket yang sesuai dengan kebutuhanmu</p>
    
    <div class="services-grid">
        @forelse($services as $service)
        <div class="service-card">
            <h3 class="service-name">{{ $service->name }}</h3>
            <div class="service-price">Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}<span style="font-size: 1.25rem; font-weight: 400; color: #6B7280;">/kg</span></div>
            <p class="service-desc">{{ $service->description ?? 'Layanan laundry premium dengan kualitas terbaik dan harga terjangkau untuk kebutuhan sehari-hari' }}</p>
            <a href="{{ route('customer.orders.create') }}" class="btn btn-primary btn-block">Pilih Layanan →</a>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #6B7280;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🔜</div>
            <p style="font-size: 1.125rem; margin: 0;">Layanan akan segera tersedia</p>
        </div>
        @endforelse
    </div>
</section>

<!-- Why Choose Us -->
<section class="section">
    <h2 class="section-title">{{ $settings->why_choose_title }}</h2>
    <p class="section-subtitle">{{ $settings->why_choose_subtitle }}</p>
    
    <div class="features">
        <div class="feature">
            <div class="feature-icon">⚡</div>
            <h3 class="feature-title">Super Cepat</h3>
            <p class="feature-desc">Proses kilat dengan hasil maksimal, tepat waktu</p>
        </div>
        
        <div class="feature">
            <div class="feature-icon">💰</div>
            <h3 class="feature-title">Harga Ramah</h3>
            <p class="feature-desc">Harga terjangkau tanpa mengurangi kualitas</p>
        </div>
        
        <div class="feature">
            <div class="feature-icon">👔</div>
            <h3 class="feature-title">Tim Pro</h3>
            <p class="feature-desc">Berpengalaman, terlatih, dan terpercaya</p>
        </div>
        
        <div class="feature">
            <div class="feature-icon">✅</div>
            <h3 class="feature-title">Dijamin Puas</h3>
            <p class="feature-desc">Garansi 100% uang kembali jika tidak puas</p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-content">
        <h2>{{ $settings->cta_section_title }}</h2>
        <p>{{ $settings->cta_section_text }}</p>
        <a href="{{ route('customer.orders.create') }}" class="btn-cta">{{ $settings->cta_button_text }}</a>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>LaundryKu</h3>
            <p style="line-height: 1.7; margin: 0;">{{ $settings->footer_description }}</p>
        </div>
        
        <div class="footer-section">
            <h3>Link Cepat</h3>
            <ul class="footer-links">
                <li><a href="{{ route('customer.orders.create') }}">Pesan Laundry</a></li>
                <li><a href="{{ route('tracking.index') }}">Lacak Pesanan</a></li>
                @auth
                    <li><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Daftar</a></li>
                @endauth
            </ul>
        </div>
        
        <div class="footer-section">
            <h3>Hubungi Kami</h3>
            <ul class="footer-links">
                <li>📧 {{ $settings->contact_email }}</li>
                <li>📱 {{ $settings->contact_phone }}</li>
                <li>📍 {{ $settings->contact_address }}</li>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p style="margin: 0;">© {{ date('Y') }} LaundryKu. Made with ❤️ for Indonesia</p>
    </div>
</footer>
@endsection
