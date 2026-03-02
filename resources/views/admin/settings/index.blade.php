@extends('layouts.admin')
@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Konfigurasi data lokasi laundry')

@section('content')
<div class="w-full">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <!-- Section: Lokasi Laundry -->
            <div class="p-6 md:p-8">
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Pengaturan Lokasi Laundry</h3>
                    <p class="text-sm text-gray-500 mt-1">Digunakan untuk menghitung jarak dan biaya antar jemput pelanggan.</p>
                </div>

                <div class="space-y-6">
                    <!-- Row: Alamat -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-6 items-start">
                        <label class="block text-sm font-medium text-gray-700 md:mt-3">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="md:col-span-3">
                            <textarea id="laundry_address" name="laundry_address" rows="3" required
                                      placeholder="Jl. Contoh No. 123, Kota, Provinsi, Kode Pos"
                                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('laundry_address') border-red-400 focus:ring-red-500 @enderror">{{ old('laundry_address', $setting->laundry_address) }}</textarea>
                            @error('laundry_address')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row: Koordinat -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-6 items-start pt-4 border-t border-gray-50">
                        <label class="block text-sm font-medium text-gray-700 md:mt-3">
                            Titik Koordinat (GPS) <span class="text-red-500">*</span>
                        </label>
                        <div class="md:col-span-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Latitude</p>
                                    <input type="text" id="laundry_latitude" name="laundry_latitude" value="{{ old('laundry_latitude', $setting->laundry_latitude) }}"
                                           required placeholder="-6.200000"
                                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('laundry_latitude') border-red-400 focus:ring-red-500 @enderror">
                                    @error('laundry_latitude')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Longitude</p>
                                    <input type="text" id="laundry_longitude" name="laundry_longitude" value="{{ old('laundry_longitude', $setting->laundry_longitude) }}"
                                           required placeholder="106.816666"
                                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('laundry_longitude') border-red-400 focus:ring-red-500 @enderror">
                                    @error('laundry_longitude')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mt-4 flex items-center gap-3">
                                <button type="button" onclick="getLocation()" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Deteksi Lokasi Otomatis
                                </button>
                                <span id="location_status" class="text-xs font-medium text-gray-500"></span>
                            </div>

                            @if($setting->laundry_latitude && $setting->laundry_longitude)
                            <div class="mt-4 p-4 bg-blue-50/50 border border-blue-100 rounded-xl">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-900">Lokasi Tersimpan Saat Ini</p>
                                        <p class="text-xs text-blue-700 mt-1 mb-2">{{ $setting->laundry_address }}</p>
                                        <a href="https://maps.google.com/?q={{ $setting->laundry_latitude }},{{ $setting->laundry_longitude }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1 text-xs font-semibold text-blue-700 hover:text-blue-800 bg-white px-2.5 py-1 rounded shadow-sm border border-blue-200 transition-colors">
                                            Buka di Google Maps →
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Footer -->
            <div class="p-6 md:p-8 border-t border-gray-100 bg-gray-50/30">
                <div class="mb-6 pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Pengaturan Footer Halaman Depan</h3>
                    <p class="text-sm text-gray-500 mt-1">Informasi yang akan ditampilkan di bagian bawah landing page (publik).</p>
                </div>

                <div class="space-y-6">
                    <!-- Row: Deskripsi Footer -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-6 items-start">
                        <label class="block text-sm font-medium text-gray-700 md:mt-3">
                            Deskripsi Singkat
                        </label>
                        <div class="md:col-span-3">
                            <textarea name="footer_company_description" rows="2"
                                      placeholder="Mitra layanan laundry premium Anda..."
                                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">{{ old('footer_company_description', $footerSettings['footer_company_description'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Row: Kontak Email & WA -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-6 items-start pt-4 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 md:mt-3">
                            Kontak Layanan
                        </label>
                        <div class="md:col-span-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Email</p>
                                    <input type="email" name="footer_email" value="{{ old('footer_email', $footerSettings['footer_email'] ?? '') }}"
                                           placeholder="support@viplaundry.com"
                                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Telepon / WhatsApp</p>
                                    <input type="text" name="footer_phone" value="{{ old('footer_phone', $footerSettings['footer_phone'] ?? '') }}"
                                           placeholder="+62 812-3456-7890"
                                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row: Alamat Pendek -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-6 items-start pt-4 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 md:mt-3">
                            Alamat Singkat
                        </label>
                        <div class="md:col-span-3">
                            <input type="text" name="footer_address" value="{{ old('footer_address', $footerSettings['footer_address'] ?? '') }}"
                                   placeholder="Jakarta, Indonesia"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Actions -->
            <div class="bg-gray-100/50 px-6 py-5 md:px-8 border-t border-gray-200 flex items-center justify-end">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-8 py-3 rounded-xl transition-colors shadow-sm focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function getLocation() {
    const statusText = document.getElementById('location_status');
    const latInput = document.getElementById('laundry_latitude');
    const lonInput = document.getElementById('laundry_longitude');
    const addressInput = document.getElementById('laundry_address');

    if (!navigator.geolocation) {
        statusText.textContent = "Geolokasi tidak didukung oleh browser ini.";
        statusText.classList.add('text-red-500');
        return;
    }

    statusText.textContent = "Mencari lokasi...";
    statusText.className = "text-xs font-medium text-blue-500";

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            latInput.value = lat.toFixed(6);
            lonInput.value = lon.toFixed(6);
            
            statusText.textContent = "Menerjemahkan koordinat ke alamat...";
            
            // Reverse geocoding to get address
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        addressInput.value = data.display_name;
                        statusText.textContent = "✓ Lokasi dan alamat berhasil dideteksi!";
                    } else {
                        statusText.textContent = "✓ Koordinat didapat, tetapi gagal menerjemahkan alamat.";
                    }
                    
                    statusText.className = "text-xs font-medium text-green-600";
                    
                    // Effect highlight to show it updated
                    latInput.classList.add('ring-2', 'ring-green-500');
                    lonInput.classList.add('ring-2', 'ring-green-500');
                    addressInput.classList.add('ring-2', 'ring-green-500');
                    
                    setTimeout(() => {
                        latInput.classList.remove('ring-2', 'ring-green-500');
                        lonInput.classList.remove('ring-2', 'ring-green-500');
                        addressInput.classList.remove('ring-2', 'ring-green-500');
                    }, 1500);
                })
                .catch(error => {
                    console.error("Geocoding error:", error);
                    statusText.textContent = "✓ Koordinat didapat, tapi layanan alamat gagal.";
                    statusText.className = "text-xs font-medium text-amber-600";
                });
        },
        function(error) {
            statusText.className = "text-xs font-medium text-red-500";
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    statusText.textContent = "Izin akses lokasi ditolak.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    statusText.textContent = "Informasi lokasi tidak tersedia.";
                    break;
                case error.TIMEOUT:
                    statusText.textContent = "Waktu permintaan lokasi habis.";
                    break;
                default:
                    statusText.textContent = "Terjadi kesalahan yang tidak diketahui.";
                    break;
            }
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}
</script>
@endpush
