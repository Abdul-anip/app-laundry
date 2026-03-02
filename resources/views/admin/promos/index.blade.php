@extends('layouts.admin')
@section('title', 'Promos')
@section('page-title', 'Promos')

@section('content')
<div x-data="{ 
        showModal: {{ $errors->any() ? 'true' : 'false' }}, 
        isEdit: {{ old('_method') == 'PUT' ? 'true' : 'false' }},
        discountType: '{{ old('discount_type', 'percent') }}',
        
        openCreateModal() {
            this.isEdit = false;
            document.getElementById('promoForm').action = '{{ route('admin.promos.store') }}';
            document.getElementById('methodInput').innerHTML = '';
            document.getElementById('formTitle').innerText = 'Tambah Promo Baru';
            document.getElementById('submitBtn').innerText = 'Simpan Promo';
            
            @if(!$errors->any())
                document.getElementById('code').value = '';
                this.discountType = 'percent';
                document.getElementById('discount_type').value = 'percent';
                document.getElementById('value').value = '';
                document.getElementById('expired_at').value = '';
                document.getElementById('is_active').checked = true;
            @endif
            
            this.showModal = true;
        },
        
        openEditModal(id, code, type, value, expired_at, is_active) {
            this.isEdit = true;
            document.getElementById('promoForm').action = '/admin/promos/' + id;
            document.getElementById('methodInput').innerHTML = `&lt;input type='hidden' name='_method' value='PUT'&gt;`.replace(/&lt;/g, '<').replace(/&gt;/g, '>');
            document.getElementById('formTitle').innerText = 'Edit Promo';
            document.getElementById('submitBtn').innerText = 'Update Promo';
            
            document.getElementById('code').value = code;
            this.discountType = type;
            document.getElementById('discount_type').value = type;
            document.getElementById('value').value = value;
            document.getElementById('expired_at').value = expired_at || '';
            document.getElementById('is_active').checked = is_active;
            
            this.showModal = true;
        }
    }">

    <div class="flex flex-wrap gap-3 items-center justify-between mb-4">
        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.promos.index') }}" class="flex gap-2 flex-wrap">
            <select name="type" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 outline-none">
                <option value="">Semua Tipe</option>
                <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Persentase</option>
                <option value="fixed"   {{ request('type') === 'fixed'   ? 'selected' : '' }}>Fixed Amount</option>
            </select>
            <select name="status" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 outline-none">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active & Valid</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="expired"  {{ request('status') === 'expired'  ? 'selected' : '' }}>Expired</option>
            </select>
            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg transition-colors">Filter</button>
            <a href="{{ route('admin.promos.index') }}" class="text-gray-500 hover:text-gray-700 text-sm px-3 py-2 transition-colors">Reset</a>
        </form>

        <button @click="openCreateModal()" type="button"
           class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors shadow-sm focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Promo
        </button>
    </div>

    {{-- ===== DESKTOP: TABLE (md ke atas) ===== --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[900px]">
                <thead class="bg-gray-50">
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="text-left px-5 py-3">Kode</th>
                    <th class="text-left px-5 py-3">Tipe</th>
                    <th class="text-left px-5 py-3">Nilai Diskon</th>
                    <th class="text-left px-5 py-3">Kadaluarsa</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-left px-5 py-3">Pemakaian</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($promos as $promo)
                @php
                    $isExpired = $promo->expired_at && $promo->expired_at->isPast();
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 font-mono font-bold text-gray-800">{{ $promo->code }}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $promo->discount_type === 'percent' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $promo->discount_type === 'percent' ? 'Persentase' : 'Fixed' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 font-semibold text-gray-800">
                        {{ $promo->discount_type === 'percent' ? $promo->value . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 {{ $isExpired ? 'text-red-500 font-medium' : 'text-gray-600' }}">
                        {{ $promo->expired_at ? $promo->expired_at->format('d M Y') : '∞ Tanpa batas' }}
                    </td>
                    <td class="px-5 py-4">
                        @if($isExpired)
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">Expired</span>
                        @elseif($promo->is_active)
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10">{{ $promo->orders_count }}x</span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button @click="openEditModal({{ $promo->id }}, '{{ $promo->code }}', '{{ $promo->discount_type }}', {{ $promo->value }}, '{{ $promo->expired_at ? $promo->expired_at->format('Y-m-d') : '' }}', {{ $promo->is_active ? 'true' : 'false' }})" type="button" class="text-primary-600 hover:text-primary-800 text-xs font-medium transition-colors">Edit</button>
                            <form method="POST" action="{{ route('admin.promos.destroy', $promo) }}" onsubmit="return confirm('Hapus promo {{ $promo->code }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-8 h-8 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Belum ada promo yang didaftarkan atau cocok dengan filter.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- ===== MOBILE: CARDS (di bawah md) ===== --}}
    <div class="md:hidden space-y-3">
        @forelse($promos as $promo)
        @php
            $isExpired = $promo->expired_at && $promo->expired_at->isPast();
        @endphp
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <span class="font-mono font-bold text-gray-900 text-base border border-dashed border-gray-300 px-2 py-1 rounded bg-gray-50">{{ $promo->code }}</span>
                @if($isExpired)
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">Expired</span>
                @elseif($promo->is_active)
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                @else
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                @endif
            </div>
            
            <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Diskon</p>
                    <p class="font-semibold text-gray-800">
                        {{ $promo->discount_type === 'percent' ? $promo->value . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 mb-0.5">Berakhir</p>
                    <p class="font-medium {{ $isExpired ? 'text-red-500' : 'text-gray-800' }}">
                        {{ $promo->expired_at ? $promo->expired_at->format('d M Y') : 'Tanpa batas' }}
                    </p>
                </div>
                <div class="col-span-2 mt-1">
                    <p class="text-xs text-gray-500 mb-0.5">Digunakan</p>
                    <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10">{{ $promo->orders_count }} kali</span>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-4 pt-3 border-t border-gray-50">
                <button @click="openEditModal({{ $promo->id }}, '{{ $promo->code }}', '{{ $promo->discount_type }}', {{ $promo->value }}, '{{ $promo->expired_at ? $promo->expired_at->format('Y-m-d') : '' }}', {{ $promo->is_active ? 'true' : 'false' }})" type="button"
                   class="text-primary-600 hover:text-primary-800 text-sm font-medium transition-colors flex items-center gap-1">
                   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                   Edit
                </button>
                <form method="POST" action="{{ route('admin.promos.destroy', $promo) }}" onsubmit="return confirm('Hapus promo {{ $promo->code }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center text-gray-500">
            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Belum ada promo yang didaftarkan.
        </div>
        @endforelse
    </div>

    <!-- Modal Form (Create & Edit) -->

    <!-- Modal Form (Create & Edit) -->
    <div x-show="showModal" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal Panel -->
                <div x-show="showModal" @click.away="showModal = false"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    
                    <form id="promoForm" method="POST" action="{{ old('_method') == 'PUT' ? route('admin.promos.update', old('id', 0)) : route('admin.promos.store') }}">
                        @csrf
                        <div id="methodInput">
                            @if(old('_method') == 'PUT') @method('PUT') @endif
                        </div>
                        <input type="hidden" name="id" value="{{ old('id') }}">
                        
                        <!-- Header Modal -->
                        <div class="bg-white px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900" id="formTitle">Tambah Promo Baru</h3>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        
                        <!-- Body Modal -->
                        <div class="px-6 py-6 space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Promo <span class="text-red-500">*</span></label>
                                <input type="text" id="code" name="code" value="{{ old('code') }}" required
                                       placeholder="DISC50" style="text-transform:uppercase"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('code') border-red-400 focus:ring-red-500 @enderror">
                                @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Diskon <span class="text-red-500">*</span></label>
                                    <select id="discount_type" name="discount_type" x-model="discountType" required
                                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('discount_type') border-red-400 focus:ring-red-500 @enderror">
                                        <option value="percent">Persentase (%)</option>
                                        <option value="fixed">Fixed Amount</option>
                                    </select>
                                    @error('discount_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nilai <span x-text="discountType === 'percent' ? '(%)' : '(Rp)'" class="text-gray-400"></span> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="value" name="value" value="{{ old('value') }}" required min="0"
                                           :placeholder="discountType === 'percent' ? '10' : '10000'"
                                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('value') border-red-400 focus:ring-red-500 @enderror">
                                    @error('value') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berakhir</label>
                                <input type="date" id="expired_at" name="expired_at" value="{{ old('expired_at') }}"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('expired_at') border-red-400 focus:ring-red-500 @enderror">
                                <p class="text-xs text-gray-400 mt-1.5">Kosongkan jika berlaku selamanya</p>
                                @error('expired_at') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-center gap-3 pt-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" class="sr-only peer"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                                <span class="text-sm font-medium text-gray-700">Promo Aktif (Bisa digunakan)</span>
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="bg-gray-50/80 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 sm:px-6">
                            <button type="button" @click="showModal = false" class="bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-medium px-5 py-2.5 rounded-xl transition-colors shadow-sm">
                                Batal
                            </button>
                            <button type="submit" id="submitBtn" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors shadow-sm focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 flex items-center gap-2">
                                Simpan Promo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
