@extends('layouts.admin')
@section('title', isset($bundle->id) ? 'Edit Bundle' : 'Tambah Bundle')
@section('page-title', isset($bundle->id) ? 'Edit Bundle' : 'Tambah Bundle')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ isset($bundle->id) ? route('admin.bundles.update', $bundle) : route('admin.bundles.store') }}">
            @csrf
            @if(isset($bundle->id)) @method('PUT') @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bundle <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $bundle->name) }}" required
                           placeholder="Contoh: Paket Hemat Keluarga"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $bundle->price) }}" required min="0"
                           placeholder="100000"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                              placeholder="Deskripsi bundle (opsional)..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">{{ old('description', $bundle->description) }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    {{ isset($bundle->id) ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.bundles.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-6 py-2.5 rounded-xl transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
