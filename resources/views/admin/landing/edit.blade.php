@extends('layouts.simple')

@section('title', 'Kelola Landing Page - LaundryKu')

@push('styles')
<style>
    body { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
    
    .landing-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }
    
    .landing-card {
        background: white;
        border-radius: 24px;
        padding: 3.5rem 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.75rem 0;
        text-align: center;
    }
    
    .page-subtitle {
        font-size: 1.125rem;
        color: #6B7280;
        margin: 0 0 3rem 0;
        text-align: center;
    }
    
    .section-divider {
        margin: 3rem 0;
        border: 0;
        border-top: 2px solid #F3F4F6;
    }
    
    .section-header {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 1.5rem 0;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F3F4F6;
    }
    
    .form-group {
        margin-bottom: 1.75rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
    }
    
    .form-input,
    .form-textarea {
        width: 100%;
        padding: 1rem 1.25rem;
        font-size: 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 14px;
        transition: all 0.2s;
        font-family: inherit;
        background: white;
    }
    
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-textarea {
        min-height: 80px;
        resize: vertical;
    }
    
    .form-hint {
        display: block;
        margin-top: 0.5rem;
        color: #6B7280;
        font-size: 0.875rem;
    }
    
    .alert {
        padding: 1.25rem 1.5rem;
        border-radius: 14px;
        margin-bottom: 2rem;
    }
    
    .alert-success {
        background: #D1FAE5;
        border: 2px solid #6EE7B7;
        color: #065F46;
    }
    
    .alert-error {
        background: #FEE2E2;
        border: 2px solid #FCA5A5;
        color: #991B1B;
    }
    
    .btn-submit {
        width: 100%;
        padding: 1.25rem 2rem;
        font-size: 1.125rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        margin-top: 2rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }
    
    .btn-cancel {
        display: inline-block;
        width: 100%;
        padding: 1rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        background: white;
        color: #667eea;
        border: 2px solid #E5E7EB;
        border-radius: 14px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }
    
    .btn-cancel:hover {
        border-color: #667eea;
        background: #F9FAFB;
    }
    
    @media (max-width: 768px) {
        .landing-container {
            padding: 2rem 1rem;
        }
        .landing-card {
            padding: 2.5rem 2rem;
        }
        .page-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="landing-container">
    <div class="landing-card">
        <h1 class="page-title">Kelola Landing Page</h1>
        <p class="page-subtitle">Edit konten halaman landing tanpa perlu ubah kode</p>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.landing.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Hero Section -->
            <h3 class="section-header">Hero Section</h3>
            
            <div class="form-group">
                <label class="form-label" for="hero_title">Judul Utama</label>
                <input type="text" name="hero_title" id="hero_title" class="form-input" value="{{ old('hero_title', $settings->hero_title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="hero_subtitle">Sub Judul</label>
                <textarea name="hero_subtitle" id="hero_subtitle" class="form-textarea" required>{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="hero_cta_primary">Text Tombol Primary</label>
                <input type="text" name="hero_cta_primary" id="hero_cta_primary" class="form-input" value="{{ old('hero_cta_primary', $settings->hero_cta_primary) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="hero_cta_secondary">Text Tombol Secondary</label>
                <input type="text" name="hero_cta_secondary" id="hero_cta_secondary" class="form-input" value="{{ old('hero_cta_secondary', $settings->hero_cta_secondary) }}" required>
            </div>

            <hr class="section-divider">

            <!-- How It Works Section -->
            <h3 class="section-header">Cara Kerja Section</h3>
            
            <div class="form-group">
                <label class="form-label" for="how_it_works_title">Judul</label>
                <input type="text" name="how_it_works_title" id="how_it_works_title" class="form-input" value="{{ old('how_it_works_title', $settings->how_it_works_title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="how_it_works_subtitle">Sub Judul</label>
                <textarea name="how_it_works_subtitle" id="how_it_works_subtitle" class="form-textarea" required>{{ old('how_it_works_subtitle', $settings->how_it_works_subtitle) }}</textarea>
            </div>

            <hr class="section-divider">

            <!-- Services Section -->
            <h3 class="section-header">Services Section</h3>
            
            <div class="form-group">
                <label class="form-label" for="services_title">Judul</label>
                <input type="text" name="services_title" id="services_title" class="form-input" value="{{ old('services_title', $settings->services_title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="services_subtitle">Sub Judul</label>
                <textarea name="services_subtitle" id="services_subtitle" class="form-textarea" required>{{ old('services_subtitle', $settings->services_subtitle) }}</textarea>
                <small class="form-hint">Item layanan dikelola di menu "Kelola Layanan"</small>
            </div>

            <hr class="section-divider">

            <!-- Why Choose Us Section -->
            <h3 class="section-header">Why Choose Us Section</h3>
            
            <div class="form-group">
                <label class="form-label" for="why_choose_title">Judul</label>
                <input type="text" name="why_choose_title" id="why_choose_title" class="form-input" value="{{ old('why_choose_title', $settings->why_choose_title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="why_choose_subtitle">Sub Judul</label>
                <textarea name="why_choose_subtitle" id="why_choose_subtitle" class="form-textarea" required>{{ old('why_choose_subtitle', $settings->why_choose_subtitle) }}</textarea>
            </div>

            <hr class="section-divider">

            <!-- CTA Section -->
            <h3 class="section-header">CTA Section</h3>
            
            <div class="form-group">
                <label class="form-label" for="cta_section_title">Judul</label>
                <input type="text" name="cta_section_title" id="cta_section_title" class="form-input" value="{{ old('cta_section_title', $settings->cta_section_title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="cta_section_text">Text</label>
                <textarea name="cta_section_text" id="cta_section_text" class="form-textarea" required>{{ old('cta_section_text', $settings->cta_section_text) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="cta_button_text">Text Tombol</label>
                <input type="text" name="cta_button_text" id="cta_button_text" class="form-input" value="{{ old('cta_button_text', $settings->cta_button_text) }}" required>
            </div>

            <hr class="section-divider">

            <!-- Footer Section -->
            <h3 class="section-header">Footer</h3>
            
            <div class="form-group">
                <label class="form-label" for="footer_description">Deskripsi</label>
                <textarea name="footer_description" id="footer_description" class="form-textarea" required>{{ old('footer_description', $settings->footer_description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_email">Email</label>
                <input type="email" name="contact_email" id="contact_email" class="form-input" value="{{ old('contact_email', $settings->contact_email) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_phone">No. Telepon</label>
                <input type="text" name="contact_phone" id="contact_phone" class="form-input" value="{{ old('contact_phone', $settings->contact_phone) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_address">Alamat</label>
                <input type="text" name="contact_address" id="contact_address" class="form-input" value="{{ old('contact_address', $settings->contact_address) }}" required>
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Kembali ke Dashboard</a>
        </form>
    </div>
</div>
@endsection
