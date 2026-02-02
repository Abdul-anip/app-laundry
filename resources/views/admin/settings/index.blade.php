@extends('layouts.simple')

@section('title', 'Pengaturan Laundry - Admin')

@push('styles')
<style>
    .settings-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 2rem;
    }
    
    .settings-card {
        background: white;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
    }
    
    .page-subtitle {
        color: #6B7280;
        margin: 0 0 2rem 0;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input,
    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }
    
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
    }
    
    .form-input[readonly] {
        background: #F3F4F6;
        cursor: not-allowed;
    }
    
    .form-hint {
        display: block;
        margin-top: 0.5rem;
        color: #6B7280;
        font-size: 0.875rem;
    }
    
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .btn-location {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .btn-location:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .btn-location.loading {
        background: #9CA3AF;
        cursor: wait;
    }
    
    .btn-submit {
        width: 100%;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.125rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1.5rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }
    
    .alert-success {
        background: #D1FAE5;
        border: 2px solid #6EE7B7;
        color: #065F46;
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .settings-container {
            padding: 1rem;
        }
        .settings-card {
            padding: 2rem 1.5rem;
        }
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-container">
    <div class="settings-card">
        <h1 class="page-title">⚙️ Pengaturan Laundry</h1>
        <p class="page-subtitle">Kelola informasi lokasi laundry Anda untuk perhitungan jarak otomatis</p>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="laundry_address">Alamat Laundry</label>
                <textarea name="laundry_address" id="laundry_address" rows="3" class="form-textarea" required>{{ old('laundry_address', $settings->laundry_address ?? '') }}</textarea>
                <small class="form-hint">Alamat lengkap lokasi laundry Anda</small>
                @error('laundry_address')
                    <small class="form-hint" style="color: #DC2626;">{{ $message }}</small>
                @enderror
            </div>

            <button type="button" onclick="getLocation()" class="btn-location" id="locationBtn">
                <span id="locationIcon">📍</span>
                <span id="locationText">Gunakan Lokasi Saya</span>
            </button>
            <small class="form-hint">Klik untuk otomatis mengisi koordinat GPS berdasarkan lokasi Anda saat ini</small>

            <div class="grid-2" style="margin-top: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" for="laundry_latitude">Latitude (GPS)</label>
                    <input type="text" name="laundry_latitude" id="laundry_latitude" class="form-input" 
                           value="{{ old('laundry_latitude', $settings->laundry_latitude ?? '') }}" 
                           placeholder="-0.123456">
                    <small class="form-hint">Isi manual atau gunakan tombol di atas</small>
                    @error('laundry_latitude')
                        <small class="form-hint" style="color: #DC2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="laundry_longitude">Longitude (GPS)</label>
                    <input type="text" name="laundry_longitude" id="laundry_longitude" class="form-input" 
                           value="{{ old('laundry_longitude', $settings->laundry_longitude ?? '') }}" 
                           placeholder="100.123456">
                    <small class="form-hint">Isi manual atau gunakan tombol di atas</small>
                    @error('laundry_longitude')
                        <small class="form-hint" style="color: #DC2626;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-submit">💾 Simpan Pengaturan</button>
        </form>
    </div>
</div>

<script>
    function getLocation() {
        const btn = document.getElementById('locationBtn');
        const icon = document.getElementById('locationIcon');
        const text = document.getElementById('locationText');
        const latField = document.getElementById('laundry_latitude');
        const lonField = document.getElementById('laundry_longitude');
        
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung oleh browser Anda');
            return;
        }
        
        // Set loading state
        btn.classList.add('loading');
        icon.textContent = '⏳';
        text.textContent = 'Mendeteksi...';
        btn.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                
                latField.value = lat.toFixed(7);
                lonField.value = lon.toFixed(7);
                
                // Success state
                btn.classList.remove('loading');
                icon.textContent = '✓';
                text.textContent = 'Lokasi Terdeteksi';
                
                setTimeout(() => {
                    icon.textContent = '📍';
                    text.textContent = 'Gunakan Lokasi Saya';
                    btn.disabled = false;
                }, 2000);
            },
            function(error) {
                btn.classList.remove('loading');
                btn.disabled = false;
                icon.textContent = '❌';
                text.textContent = 'Gagal';
                
                let errorMsg = '';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMsg = 'Izin lokasi ditolak. Mohon izinkan akses lokasi di browser Anda.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMsg = 'Informasi lokasi tidak tersedia.';
                        break;
                    case error.TIMEOUT:
                        errorMsg = 'Timeout saat mencoba mendapatkan lokasi.';
                        break;
                    default:
                        errorMsg = 'Terjadi kesalahan saat mendapatkan lokasi.';
                }
                
                alert(errorMsg);
                
                setTimeout(() => {
                    icon.textContent = '📍';
                    text.textContent = 'Gunakan Lokasi Saya';
                }, 3000);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }
</script>
@endsection
