@extends('layouts.customer')

@section('title', 'Buat Pesanan - VIP Laundry')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Buat Pesanan Baru</h1>
    <p class="text-gray-600">Isi formulir di bawah untuk memesan layanan laundry</p>
</div>

@if ($errors->any())
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
        <div class="font-medium mb-2">Terdapat kesalahan:</div>
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('customer.orders.store') }}" method="POST" class="space-y-6">
    @csrf

    <!-- Customer Information -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
        <h2 class="mb-4 text-lg font-semibold text-gray-900">Informasi Pelanggan</h2>
        
        <div class="space-y-4">
            <div>
                <label for="customer_name" class="block mb-2 text-sm font-medium text-gray-900">Nama Pelanggan</label>
                <input type="text" id="customer_name" name="customer_name" value="{{ auth()->user()->name }}" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 cursor-not-allowed">
                <p class="mt-1 text-xs text-gray-500">Nama otomatis diambil dari akun Anda</p>
            </div>

            <div>
                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ auth()->user()->phone }}" {{ auth()->user()->phone ? 'readonly' : 'required' }} class="bg-{{ auth()->user()->phone ? 'gray-50' : 'white' }} border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 {{ auth()->user()->phone ? 'cursor-not-allowed' : '' }}" placeholder="08123456789">
                @if(auth()->user()->phone)
                    <p class="mt-1 text-xs text-gray-500">Nomor telepon otomatis diambil dari akun Anda</p>
                @else
                    <p class="mt-1 text-xs text-yellow-600">⚠️ Lengkapi nomor telepon untuk memesan</p>
                @endif
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="address" class="block text-sm font-medium text-gray-900">
                        Alamat Penjemputan
                        <span class="text-red-500">*</span>
                    </label>
                    <button type="button" onclick="getLocation()" id="locationBtn" title="Klik untuk mendeteksi lokasi Anda secara otomatis menggunakan GPS" class="inline-flex items-center gap-1.5 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2 transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg id="locationIcon" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        <span id="locationText" class="font-semibold">🎯 Deteksi Lokasi Saya</span>
                    </button>
                </div>
                <div class="relative">
                    <textarea id="address" name="address" rows="4" class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border-2 border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02, Kelurahan ABC, Kecamatan XYZ, Kota/Kabupaten, Provinsi" required>{{ old('address', auth()->user()->address) }}</textarea>
                    <!-- Progress Indicator -->
                    <div id="addressProgress" class="hidden mt-2 p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="progressText" class="text-sm font-medium text-blue-700">Mengambil lokasi GPS...</span>
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-500" id="locationHint">
                    💡 Klik tombol "<strong>🎯 Deteksi Lokasi Saya</strong>" untuk mengisi alamat secara otomatis menggunakan GPS
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="latitude" class="block mb-2 text-sm font-medium text-gray-900">Latitude (GPS)</label>
                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Terisi otomatis</p>
                </div>
                <div>
                    <label for="longitude" class="block mb-2 text-sm font-medium text-gray-900">Longitude (GPS)</label>
                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Terisi otomatis</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Selection -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
        <h2 class="mb-4 text-lg font-semibold text-gray-900">Pilih Layanan</h2>
        
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900">Tipe Pesanan</label>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <input type="radio" id="type_service" name="order_type" value="service" {{ old('order_type', 'service') == 'service' ? 'checked' : '' }} class="hidden peer" onclick="toggleOrderType()">
                    <label for="type_service" class="inline-flex items-center justify-center w-full p-4 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary-600 peer-checked:bg-primary-50 peer-checked:text-primary-600 hover:text-gray-600 hover:bg-gray-50">
                        <div class="block">
                            <div class="w-full text-base font-semibold">Layanan Kiloan</div>
                            <div class="w-full text-xs">Per kilogram</div>
                        </div>
                    </label>
                </div>
                <div>
                    <input type="radio" id="type_bundle" name="order_type" value="bundle" {{ old('order_type') == 'bundle' ? 'checked' : '' }} class="hidden peer" onclick="toggleOrderType()">
                    <label for="type_bundle" class="inline-flex items-center justify-center w-full p-4 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary-600 peer-checked:bg-primary-50 peer-checked:text-primary-600 hover:text-gray-600 hover:bg-gray-50">
                        <div class="block">
                            <div class="w-full text-base font-semibold">Paket Satuan</div>
                            <div class="w-full text-xs">Bundle items</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div id="service_section" class="space-y-4">
            <div>
                <label for="service_id" class="block mb-2 text-sm font-medium text-gray-900">Pilih Service (Per Kg)</label>
                <select id="service_id" name="service_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    <option value="">-- Pilih Service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} - Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="weight_kg" class="block mb-2 text-sm font-medium text-gray-900">Perkiraan Berat (Kg)</label>
                <input type="number" id="weight_kg" name="weight_kg" step="0.1" min="1" value="{{ old('weight_kg') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" placeholder="1.0">
            </div>
        </div>

        <div id="bundle_section" class="hidden">
            <label for="bundle_id" class="block mb-2 text-sm font-medium text-gray-900">Pilih Paket (Bundle)</label>
            <select id="bundle_id" name="bundle_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                <option value="">-- Pilih Paket --</option>
                @foreach($bundles as $bundle)
                    <option value="{{ $bundle->id }}" {{ old('bundle_id') == $bundle->id ? 'selected' : '' }}>
                        {{ $bundle->name }} - Rp {{ number_format($bundle->price, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <label for="fabric_type" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kain (Opsional)</label>
            <input type="text" id="fabric_type" name="fabric_type" value="{{ old('fabric_type') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" placeholder="Contoh: Katun, Sutra, Wool">
        </div>

        <div class="mt-4">
            <label for="notes" class="block mb-2 text-sm font-medium text-gray-900">Catatan Tambahan (Opsional)</label>
            <textarea id="notes" name="notes" rows="2" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Tuliskan catatan khusus untuk pesanan Anda">{{ old('notes') }}</textarea>
        </div>
        
        <div>
            <label for="promo_code" class="block mb-2 text-sm font-medium text-gray-900">Kode Promo (Opsional)</label>
            <input type="text" id="promo_code" name="promo_code" value="{{ old('promo_code') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" placeholder="Masukkan kode promo">
        </div>
    </div>

    <!-- Pickup & Payment -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
        <h2 class="mb-4 text-lg font-semibold text-gray-900">Detail Penjemputan</h2>
        

        <div class="mb-4">
            <label for="distance_km" class="block mb-2 text-sm font-medium text-gray-900">Jarak Penjemputan (KM)</label>
            <input type="number" id="distance_km" name="distance_km" step="0.1" min="0" value="{{ old('distance_km') }}" readonly required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
            <p class="mt-1 text-xs text-gray-500">Jarak dihitung otomatis dari GPS (gratis ≤ 2 KM, Rp 5.000/km untuk > 2 KM)</p>
            <div id="pickup-fee-info" class="mt-2 text-sm font-semibold"></div>
        </div>

    </div>

    <!-- Submit Buttons -->
    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end mt-6">
        <a href="{{ route('customer.dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-gray-900 bg-white border-2 border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-6 py-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span>Batalkan Pesanan</span>
        </a>
        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-lg text-sm px-6 py-3 shadow-lg hover:shadow-xl transition-all">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"></path>
            </svg>
            <span class="font-semibold">Buat Pesanan Sekarang</span>
        </button>
    </div>
</form>

@push('scripts')
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

function getLocation() {
    const btn = document.getElementById('locationBtn');
    const icon = document.getElementById('locationIcon');
    const text = document.getElementById('locationText');
    const hint = document.getElementById('locationHint');
    const addressField = document.getElementById('address');
    const latField = document.getElementById('latitude');
    const lonField = document.getElementById('longitude');
    const progress = document.getElementById('addressProgress');
    const progressText = document.getElementById('progressText');
    
    if (!navigator.geolocation) {
        showError('❌ Browser Anda tidak mendukung GPS. Silakan masukkan alamat secara manual.');
        return;
    }
    
    // Set loading state
    btn.disabled = true;
    btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
    btn.classList.add('bg-gray-400');
    icon.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    text.textContent = '⏳ Mendeteksi...';
    progress.classList.remove('hidden');
    progressText.textContent = 'Mengakses GPS perangkat Anda...';
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            const accuracy = position.coords.accuracy;
            
            latField.value = lat.toFixed(6);
            lonField.value = lon.toFixed(6);
            
            progressText.textContent = `✓ GPS terdeteksi! Akurasi: ±${Math.round(accuracy)}m. Mengambil alamat...`;
            
            // Calculate distance
            calculateDistance(lat, lon);
            
            // Reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        addressField.value = data.display_name;
                        showSuccess(lat, lon, accuracy);
                    } else {
                        addressField.value = `Koordinat: ${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                        showSuccess(lat, lon, accuracy);
                    }
                })
                .catch(error => {
                    console.error('Reverse geocoding error:', error);
                    addressField.value = `Koordinat GPS: ${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                    showSuccess(lat, lon, accuracy);
                });
        },
        function(error) {
            let errorMsg = '';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMsg = '🚫 Izin lokasi ditolak. Mohon izinkan akses GPS di browser Anda.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMsg = '📡 Sinyal GPS tidak tersedia. Pastikan GPS aktif dan Anda berada di area terbuka.';
                    break;
                case error.TIMEOUT:
                    errorMsg = '⏱️ Waktu habis mencari GPS. Coba lagi atau masukkan alamat manual.';
                    break;
                default:
                    errorMsg = '❌ Gagal mendeteksi lokasi. Silakan coba lagi atau masukkan alamat manual.';
            }
            showError(errorMsg);
        },
        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }
    );
    
    function showSuccess(lat, lon, accuracy) {
        btn.disabled = false;
        btn.classList.remove('bg-gray-400');
        btn.classList.add('bg-green-600', 'hover:bg-green-700');
        icon.innerHTML = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
        text.textContent = '✓ Lokasi Terdeteksi!';
        
        progress.classList.remove('bg-blue-50', 'border-blue-500');
        progress.classList.add('bg-green-50', 'border-green-500');
        progressText.innerHTML = `
            <div class="text-green-700">
                <div class="font-bold mb-1">✅ Berhasil mendeteksi lokasi!</div>
                <div class="text-xs">📍 Koordinat: ${lat.toFixed(6)}, ${lon.toFixed(6)}</div>
                <div class="text-xs">🎯 Akurasi: ±${Math.round(accuracy)} meter</div>
            </div>
        `;
        
        hint.innerHTML = '✅ <strong class="text-green-600">Lokasi berhasil dideteksi dan alamat terisi otomatis!</strong>';
        
        setTimeout(() => {
            progress.classList.add('hidden');
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            icon.innerHTML = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>';
            text.textContent = '🎯 Deteksi Lokasi Saya';
            hint.innerHTML = '💡 Klik tombol "<strong>🎯 Deteksi Lokasi Saya</strong>" untuk mengisi alamat secara otomatis menggunakan GPS';
        }, 5000);
    }
    
    function showError(message) {
        btn.disabled = false;
        btn.classList.remove('bg-gray-400');
        btn.classList.add('bg-red-600', 'hover:bg-red-700');
        icon.innerHTML = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
        text.textContent = '❌ Gagal';
        
        progress.classList.remove('hidden', 'bg-blue-50', 'border-blue-500');
        progress.classList.add('bg-red-50', 'border-red-500');
        progressText.innerHTML = `<div class="text-red-700 font-medium">${message}</div>`;
        
        hint.innerHTML = `<span class="text-red-600 font-medium">${message}</span>`;
        
        setTimeout(() => {
            progress.classList.add('hidden');
            btn.classList.remove('bg-red-600', 'hover:bg-red-700');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            icon.innerHTML = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>';
            text.textContent = '🎯 Deteksi Lokasi Saya';
            hint.innerHTML = '💡 Klik tombol "<strong>🎯 Deteksi Lokasi Saya</strong>" untuk mengisi alamat secara otomatis menggunakan GPS';
        }, 6000);
    }
}

function calculateDistance(customerLat, customerLon) {
    fetch('/api/laundry-location')
        .then(response => response.json())
        .then(data => {
            const laundryLat = data.latitude;
            const laundryLon = data.longitude;
            
            const R = 6371;
            const dLat = (customerLat - laundryLat) * Math.PI / 180;
            const dLon = (customerLon - laundryLon) * Math.PI / 180;
            
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(laundryLat * Math.PI / 180) *
                      Math.cos(customerLat * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const distance = R * c;
            
            document.getElementById('distance_km').value = distance.toFixed(1);
            updatePickupFee(distance.toFixed(1));
        })
        .catch(error => {
            console.error('Error fetching laundry location:', error);
            document.getElementById('distance_km').readOnly = false;
            document.getElementById('distance_km').classList.remove('bg-gray-50', 'cursor-not-allowed');
            document.getElementById('distance_km').classList.add('bg-white');
        });
}

function updatePickupFee(distance) {
    const feeInfoDiv = document.getElementById('pickup-fee-info');
    const dist = parseFloat(distance);
    
    if (dist <= 2) {
        feeInfoDiv.innerHTML = `<span class="text-green-600">✅ GRATIS Ongkir (jarak ${distance} km)</span>`;
    } else {
        const fee = (dist - 2) * 5000;
        feeInfoDiv.innerHTML = `<span class="text-yellow-600">💰 Ongkir: Rp ${fee.toLocaleString('id-ID')} (jarak ${distance} km)</span>`;
        feeInfoDiv.innerHTML += `<div class="text-xs text-gray-500 mt-1">Note: Harga laundry akan dihitung setelah ditimbang oleh petugas.</div>`;
    }
}
</script>
@endpush

@endsection
