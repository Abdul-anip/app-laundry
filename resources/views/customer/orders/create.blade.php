@extends('layouts.simple')

@section('title', 'Buat Pesanan - LaundryKu')

@push('styles')
<style>
    body { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
    
    .order-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }
    
    .order-card {
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
    .form-select,
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
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    .form-hint {
        display: block;
        margin-top: 0.5rem;
        color: #6B7280;
        font-size: 0.875rem;
    }
    
    /* Order Type Toggle */
    .order-type-toggle {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .toggle-option {
        position: relative;
    }
    
    .toggle-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }
    
    .toggle-label {
        display: block;
        padding: 1.25rem;
        background: #F9FAFB;
        border: 2px solid #E5E7EB;
        border-radius: 14px;
        text-align: center;
        font-weight: 600;
        color: #6B7280;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .toggle-option input[type="radio"]:checked + .toggle-label {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .toggle-label:hover {
        border-color: #667eea;
    }
    
    /* Grid for date/time */
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .section-divider {
        margin: 3rem 0;
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
    
    .alert {
        padding: 1.25rem 1.5rem;
        border-radius: 14px;
        margin-bottom: 2rem;
    }
    
    .alert-error {
        background: #FEE2E2;
        border: 2px solid #FCA5A5;
        color: #991B1B;
    }
    
    .alert ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    
    .alert li {
        margin-bottom: 0.25rem;
    }
    
    /* Location Button */
    .btn-location {
        position: absolute;
        top: 8px;
        right: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.625rem 1rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    
    .btn-location:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .btn-location:active {
        transform: translateY(0);
    }
    
    .btn-location.loading {
        background: #9CA3AF;
        cursor: wait;
    }
    
    .btn-location.success {
        background: #10B981;
    }
    
    @media (max-width: 768px) {
        .order-container {
            padding: 2rem 1rem;
        }
        .order-card {
            padding: 2.5rem 2rem;
        }
        .page-title {
            font-size: 2rem;
        }
        .grid-2 {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }
        .order-type-toggle {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="order-container">
    <div class="order-card">
        <h1 class="page-title">Buat Pesanan Baru</h1>
        <p class="page-subtitle">Isi formulir di bawah untuk memesan layanan laundry</p>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.orders.store') }}" method="POST">
            @csrf

            <!-- Customer Information -->
            <h3 class="section-header">Informasi Pelanggan</h3>
            
            <div class="form-group">
                <label class="form-label" for="customer_name">Nama Pelanggan</label>
                <input type="text" name="customer_name" id="customer_name" class="form-input" value="{{ auth()->user()->name }}" readonly style="background: #F9FAFB; cursor: not-allowed;">
                <small class="form-hint">Nama otomatis diambil dari akun Anda</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" class="form-input" 
                       value="{{ auth()->user()->phone }}" 
                       {{ auth()->user()->phone ? 'readonly style=background:#F9FAFB;cursor:not-allowed;' : 'required placeholder=08123456789' }}>
                @if(auth()->user()->phone)
                    <small class="form-hint">Nomor telepon otomatis diambil dari akun Anda</small>
                @else
                    <small class="form-hint" style="color: #d97706;">⚠️ Lengkapi nomor telepon untuk memesan</small>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Alamat Penjemputan</label>
                <div style="position: relative;">
                    <textarea name="address" id="address" rows="3" class="form-textarea" required>{{ old('address', auth()->user()->address) }}</textarea>
                    <button type="button" onclick="getLocation()" class="btn-location" id="locationBtn">
                        <span id="locationIcon">📍</span>
                        <span id="locationText">Gunakan Lokasi Saya</span>
                    </button>
                </div>
                <small class="form-hint" id="locationHint">Klik tombol untuk otomatis mendeteksi lokasi Anda</small>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="latitude">Latitude (GPS)</label>
                    <input type="text" name="latitude" id="latitude" class="form-input" value="{{ old('latitude') }}" readonly style="background: #F3F4F6; cursor: not-allowed;">
                    <small class="form-hint">Koordinat akan terisi otomatis dari GPS</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="longitude">Longitude (GPS)</label>
                    <input type="text" name="longitude" id="longitude" class="form-input" value="{{ old('longitude') }}" readonly style="background: #F3F4F6; cursor: not-allowed;">
                    <small class="form-hint">Koordinat akan terisi otomatis dari GPS</small>
                </div>
            </div>

            <div class="section-divider"></div>

            <!-- Service Selection -->
            <h3 class="section-header">Pilih Layanan</h3>
            
            <div class="form-group">
                <label class="form-label">Tipe Pesanan</label>
                <div class="order-type-toggle">
                    <div class="toggle-option">
                        <input type="radio" name="order_type" value="service" id="type_service" {{ old('order_type', 'service') == 'service' ? 'checked' : '' }} onclick="toggleOrderType()">
                        <label for="type_service" class="toggle-label">Layanan Kiloan</label>
                    </div>
                    <div class="toggle-option">
                        <input type="radio" name="order_type" value="bundle" id="type_bundle" {{ old('order_type') == 'bundle' ? 'checked' : '' }} onclick="toggleOrderType()">
                        <label for="type_bundle" class="toggle-label">Paket Satuan</label>
                    </div>
                </div>
            </div>

            <div id="service_section">
                <div class="form-group">
                    <label class="form-label" for="service_id">Pilih Service (Per Kg)</label>
                    <select name="service_id" id="service_id" class="form-select">
                        <option value="">-- Pilih Service --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} - Rp {{ number_format($service->price_per_kg, 0, ',', '.') }} /kg
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="weight_kg">Perkiraan Berat (Kg)</label>
                    <input type="number" name="weight_kg" id="weight_kg" step="0.1" min="1" class="form-input" value="{{ old('weight_kg') }}" placeholder="1.0">
                </div>
            </div>

            <div id="bundle_section" class="hidden">
                <div class="form-group">
                    <label class="form-label" for="bundle_id">Pilih Paket (Bundle)</label>
                    <select name="bundle_id" id="bundle_id" class="form-select">
                        <option value="">-- Pilih Paket --</option>
                        @foreach($bundles as $bundle)
                            <option value="{{ $bundle->id }}" {{ old('bundle_id') == $bundle->id ? 'selected' : '' }}>
                                {{ $bundle->name }} - Rp {{ number_format($bundle->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="fabric_type">Jenis Kain (Opsional)</label>
                <input type="text" name="fabric_type" id="fabric_type" class="form-input" placeholder="Contoh: Katun, Sutra, Wool" value="{{ old('fabric_type') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="notes">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" id="notes" rows="2" class="form-textarea" placeholder="Tuliskan catatan khusus untuk pesanan Anda">{{ old('notes') }}</textarea>
            </div>

            <div class="section-divider"></div>

            <!-- Pickup & Payment -->
            <h3 class="section-header">Detail Penjemputan & Pembayaran</h3>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="pickup_date">Tanggal Penjemputan</label>
                    <input type="date" name="pickup_date" id="pickup_date" class="form-input" value="{{ old('pickup_date') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="pickup_time">Waktu Penjemputan</label>
                    <input type="time" name="pickup_time" id="pickup_time" class="form-input" value="{{ old('pickup_time') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="distance_km">Jarak Penjemputan (KM)</label>
                <input type="number" name="distance_km" id="distance_km" step="0.1" min="0" class="form-input" value="{{ old('distance_km') }}" required>
                <small class="form-hint">Gratis ongkir untuk jarak ≤ 2 KM. Lebih dari itu Rp 5.000/km</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="promo_code">Kode Promo (Opsional)</label>
                <input type="text" name="promo_code" id="promo_code" class="form-input" placeholder="Masukkan kode promo" value="{{ old('promo_code') }}">
            </div>

            <button type="submit" class="btn-submit">Buat Pesanan</button>
            <a href="{{ route('customer.dashboard') }}" class="btn-cancel">Batal</a>
        </form>
    </div>
</div>

<script>
    function toggleOrderType() {
        const isService = document.getElementById('type_service').checked;
        const serviceSection = document.getElementById('service_section');
        const bundleSection = document.getElementById('bundle_section');

        if (isService) {
            serviceSection.classList.remove('hidden');
            bundleSection.classList.add('hidden');
        } else {
            serviceSection.classList.add('hidden');
            bundleSection.classList.remove('hidden');
        }
    }
    toggleOrderType();
    
    // Geolocation Function
    function getLocation() {
        const btn = document.getElementById('locationBtn');
        const icon = document.getElementById('locationIcon');
        const text = document.getElementById('locationText');
        const hint = document.getElementById('locationHint');
        const addressField = document.getElementById('address');
        const latField = document.getElementById('latitude');
        const lonField = document.getElementById('longitude');
        
        // Check if browser supports geolocation
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung oleh browser Anda');
            return;
        }
        
        // Set loading state
        btn.classList.add('loading');
        icon.textContent = '⏳';
        text.textContent = 'Mendeteksi...';
        btn.disabled = true;
        
        // Get position
        navigator.geolocation.getCurrentPosition(
            // Success callback
            function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                
                // Store coordinates
                latField.value = lat;
                lonField.value = lon;
                
                // Reverse geocoding to get address
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.display_name) {
                            addressField.value = data.display_name;
                        } else {
                            addressField.value = `Latitude: ${lat}, Longitude: ${lon}`;
                        }
                        
                        // Success state
                        btn.classList.remove('loading');
                        btn.classList.add('success');
                        icon.textContent = '✓';
                        text.textContent = 'Lokasi Terdeteksi';
                        hint.textContent = `Koordinat: ${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                        hint.style.color = '#10B981';
                        
                        // Reset after 3 seconds
                        setTimeout(() => {
                            btn.classList.remove('success');
                            icon.textContent = '📍';
                            text.textContent = 'Gunakan Lokasi Saya';
                            btn.disabled = false;
                        }, 3000);
                    })
                    .catch(error => {
                        // If reverse geocoding fails, still use coordinates
                        addressField.value = `Latitude: ${lat}, Longitude: ${lon}`;
                        
                        btn.classList.remove('loading');
                        btn.classList.add('success');
                        icon.textContent = '✓';
                        text.textContent = 'Lokasi Terdeteksi';
                        hint.textContent = `Koordinat: ${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                        hint.style.color = '#10B981';
                        
                        setTimeout(() => {
                            btn.classList.remove('success');
                            icon.textContent = '📍';
                            text.textContent = 'Gunakan Lokasi Saya';
                            btn.disabled = false;
                        }, 3000);
                    });
            },
            // Error callback
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
                
                hint.textContent = errorMsg;
                hint.style.color = '#EF4444';
                
                // Reset after 4 seconds
                setTimeout(() => {
                    icon.textContent = '📍';
                    text.textContent = 'Gunakan Lokasi Saya';
                    hint.textContent = 'Klik tombol untuk otomatis mendeteksi lokasi Anda';
                    hint.style.color = '#6B7280';
                }, 4000);
            },
            // Options
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }
</script>
@endsection
