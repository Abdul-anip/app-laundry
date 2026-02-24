@extends('layouts.admin')
@section('title', isset($promo->id) ? 'Edit Promo' : 'Tambah Promo')
@section('page-title', isset($promo->id) ? 'Edit Promo' : 'Tambah Promo')

@section('content')
<div class="max-w-xl" x-data="{ discountType: '{{ old('discount_type', $promo->discount_type ?? 'percent') }}' }">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ isset($promo->id) ? route('admin.promos.update', $promo) : route('admin.promos.store') }}">
            @csrf
            @if(isset($promo->id)) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Promo <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $promo->code) }}" required
                           placeholder="DISC50" style="text-transform:uppercase"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-primary-500 outline-none">
                    @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Diskon <span class="text-red-500">*</span></label>
                    <select name="discount_type" x-model="discountType" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        <option value="percent">Persentase (%)</option>
                        <option value="fixed">Fixed Amount (Rp)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nilai Diskon <span x-text="discountType === 'percent' ? '(%)' : '(Rp)'" class="text-gray-400"></span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="value" value="{{ old('value', $promo->value) }}" required min="0"
                           :placeholder="discountType === 'percent' ? '10' : '10000'"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                    <input type="date" name="expired_at" value="{{ old('expired_at', $promo->expired_at?->format('Y-m-d')) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tanpa batas waktu</p>
                </div>

                <div class="flex items-center gap-3 mt-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                               {{ old('is_active', $promo->is_active ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">Aktif</span>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    {{ isset($promo->id) ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.promos.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-6 py-2.5 rounded-xl transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
