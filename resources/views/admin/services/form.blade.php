@extends('layouts.admin')
@section('title', isset($service->id) ? 'Edit Service' : 'Tambah Service')
@section('page-title', isset($service->id) ? 'Edit Service' : 'Tambah Service')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ isset($service->id) ? route('admin.services.update', $service) : route('admin.services.store') }}">
            @csrf
            @if(isset($service->id)) @method('PUT') @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Service <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $service->name) }}" required
                           placeholder="Contoh: Laundry Express"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Kg (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_kg" value="{{ old('price_per_kg', $service->price_per_kg) }}" required min="0" step="500"
                           placeholder="5000"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none @error('price_per_kg') border-red-400 @enderror">
                    @error('price_per_kg') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    {{ isset($service->id) ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.services.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-6 py-2.5 rounded-xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
