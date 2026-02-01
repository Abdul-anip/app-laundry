@extends('layouts.simple')

@section('title', 'Edit Services Section - LaundryKu')

@push('styles')
<style>
    body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; margin: 0; padding: 0; }
    .landing-container { max-width: 700px; margin: 0 auto; padding: 3rem 2rem; }
    .landing-card { background: white; border-radius: 24px; padding: 3rem 2.5rem; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15); }
    .page-title { font-size: 2.25rem; font-weight: 700; color: #1F2937; margin: 0 0 0.5rem 0; }
    .page-subtitle { font-size: 1rem; color: #6B7280; margin: 0 0 2.5rem 0; }
    .form-group { margin-bottom: 1.75rem; }
    .form-label { display: block; font-size: 0.95rem; font-weight: 600; color: #374151; margin-bottom: 0.75rem; }
    .form-input, .form-textarea { width: 100%; padding: 1rem 1.25rem; font-size: 1rem; border: 2px solid #E5E7EB; border-radius: 14px; transition: all 0.2s; font-family: inherit; background: white; }
    .form-input:focus, .form-textarea:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
    .form-textarea { min-height: 100px; resize: vertical; }
    .alert { padding: 1.25rem 1.5rem; border-radius: 14px; margin-bottom: 2rem; }
    .alert-success { background: #D1FAE5; border: 2px solid #6EE7B7; color: #065F46; font-weight: 500; }
    .alert-error { background: #FEE2E2; border: 2px solid #FCA5A5; color: #991B1B; }
    .form-hint { display: block; margin-top: 0.5rem; color: #6B7280; font-size: 0.875rem; }
    .btn-submit { width: 100%; padding: 1.125rem 2rem; font-size: 1.125rem; font-weight: 700; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 14px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); margin-top: 1rem; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4); }
    .btn-back { display: inline-block; width: 100%; padding: 1rem 2rem; font-size: 1rem; font-weight: 600; background: white; color: #667eea; border: 2px solid #E5E7EB; border-radius: 14px; text-align: center; text-decoration: none; transition: all 0.3s ease; margin-top: 1rem; }
    .btn-back:hover { border-color: #667eea; background: #F9FAFB; }
    @media (max-width: 768px) { .landing-container { padding: 2rem 1rem; } .landing-card { padding: 2.5rem 2rem; } .page-title { font-size: 1.875rem; } }
</style>
@endpush

@section('content')
<div class="landing-container">
    <div class="landing-card">
        <h1 class="page-title">Edit Services Section</h1>
        <p class="page-subtitle">Update judul dan subtitle pada bagian Layanan</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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

        <form action="{{ route('admin.landing.services.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="services_title">Judul</label>
                <input type="text" name="services_title" id="services_title" class="form-input" value="{{ old('services_title', $settings->services_title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="services_subtitle">Sub Judul</label>
                <textarea name="services_subtitle" id="services_subtitle" class="form-textarea" required>{{ old('services_subtitle', $settings->services_subtitle) }}</textarea>
                <small class="form-hint">Item layanan dikelola di menu "Kelola Layanan"</small>
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="{{ route('admin.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>
        </form>
    </div>
</div>
@endsection
