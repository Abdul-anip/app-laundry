@extends('layouts.admin')
@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Konfigurasi data lokasi laundry')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Laundry <span class="text-red-500">*</span>
                    </label>
                    <textarea name="laundry_address" rows="3" required
                              placeholder="Jl. Contoh No. 123, Kota, Provinsi, Kode Pos"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('laundry_address') border-red-400 @enderror">{{ old('laundry_address', $setting->laundry_address) }}</textarea>
                    @error('laundry_address')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Latitude <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="laundry_latitude" value="{{ old('laundry_latitude', $setting->laundry_latitude) }}"
                           required placeholder="-6.200000"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none @error('laundry_latitude') border-red-400 @enderror">
                    @error('laundry_latitude')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Longitude <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="laundry_longitude" value="{{ old('laundry_longitude', $setting->laundry_longitude) }}"
                           required placeholder="106.816666"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none @error('laundry_longitude') border-red-400 @enderror">
                    @error('laundry_longitude')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">Koordinat digunakan di peta untuk customer. Gunakan Google Maps untuk mendapatkan koordinat yang tepat.</p>
                </div>
            </div>

            @if($setting->laundry_latitude && $setting->laundry_longitude)
            <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-500 mb-2">📍 Lokasi saat ini:</p>
                <a href="https://maps.google.com/?q={{ $setting->laundry_latitude }},{{ $setting->laundry_longitude }}"
                   target="_blank"
                   class="text-xs text-primary-600 hover:text-primary-800 underline">
                    Lihat di Google Maps →
                </a>
                <p class="text-xs text-gray-600 mt-1">{{ $setting->laundry_address }}</p>
            </div>
            @endif

            <button type="submit"
                    class="mt-6 w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors text-sm">
                Simpan Settings
            </button>
        </form>
    </div>
</div>
@endsection
