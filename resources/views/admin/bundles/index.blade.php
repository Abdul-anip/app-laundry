@extends('layouts.admin')
@section('title', 'Bundles')
@section('page-title', 'Bundles')

@section('content')
<div x-data="{ 
        showModal: {{ $errors->any() ? 'true' : 'false' }}, 
        isEdit: {{ old('_method') == 'PUT' ? 'true' : 'false' }},
        
        openCreateModal() {
            this.isEdit = false;
            document.getElementById('bundleForm').action = '{{ route('admin.bundles.store') }}';
            document.getElementById('methodInput').innerHTML = '';
            document.getElementById('formTitle').innerText = 'Tambah Bundle Baru';
            document.getElementById('submitBtn').innerText = 'Simpan Bundle';
            
            @if(!$errors->any())
                document.getElementById('name').value = '';
                document.getElementById('price').value = '';
                document.getElementById('description').value = '';
            @endif
            
            this.showModal = true;
        },
        
        openEditModal(id, name, price, description) {
            this.isEdit = true;
            document.getElementById('bundleForm').action = '/admin/bundles/' + id;
            document.getElementById('methodInput').innerHTML = `&lt;input type='hidden' name='_method' value='PUT'&gt;`.replace(/&lt;/g, '<').replace(/&gt;/g, '>');
            document.getElementById('formTitle').innerText = 'Edit Bundle';
            document.getElementById('submitBtn').innerText = 'Update Bundle';
            
            document.getElementById('name').value = name;
            document.getElementById('price').value = price;
            document.getElementById('description').value = description;
            
            this.showModal = true;
        }
    }">

    <div class="flex justify-between items-center mb-4">
        <p class="text-sm text-gray-500">{{ $bundles->count() }} bundle terdaftar</p>
        <button @click="openCreateModal()" type="button"
           class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors shadow-sm focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Bundle
        </button>
    </div>

    {{-- ===== DESKTOP: TABLE (md ke atas) ===== --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
                <thead class="bg-gray-50">
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="text-left px-5 py-3">Nama Bundle</th>
                    <th class="text-left px-5 py-3">Harga</th>
                    <th class="text-left px-5 py-3">Deskripsi</th>
                    <th class="text-left px-5 py-3">Orders</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bundles as $bundle)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 font-semibold text-gray-800">{{ $bundle->name }}</td>
                    <td class="px-5 py-4 font-medium text-gray-900">Rp {{ number_format($bundle->price, 0, ',', '.') }}</td>
                    <td class="px-5 py-4 text-gray-500 max-w-xs truncate">{{ $bundle->description ?? '-' }}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10">{{ $bundle->orders_count }}</span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button @click="openEditModal({{ $bundle->id }}, '{{ addslashes($bundle->name) }}', {{ $bundle->price }}, '{{ addslashes($bundle->description ?? '') }}')" type="button" class="text-primary-600 hover:text-primary-800 text-xs font-medium transition-colors">Edit</button>
                            <form method="POST" action="{{ route('admin.bundles.destroy', $bundle) }}" onsubmit="return confirm('Hapus bundle {{ addslashes($bundle->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-8 h-8 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Belum ada paket bundle yang didaftarkan.
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
        @forelse($bundles as $bundle)
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="font-bold text-gray-900 text-base">{{ $bundle->name }}</span>
                <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10">
                    {{ $bundle->orders_count }} order
                </span>
            </div>
            
            <div class="mb-3">
                <p class="text-sm font-semibold text-primary-600 mb-1">Rp {{ number_format($bundle->price, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 line-clamp-2">{{ $bundle->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>
            
            <div class="flex items-center justify-end gap-4 pt-3 border-t border-gray-50">
                <button @click="openEditModal({{ $bundle->id }}, '{{ addslashes($bundle->name) }}', {{ $bundle->price }}, '{{ addslashes($bundle->description ?? '') }}')" type="button"
                   class="text-primary-600 hover:text-primary-800 text-sm font-medium transition-colors flex items-center gap-1">
                   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                   Edit
                </button>
                <form method="POST" action="{{ route('admin.bundles.destroy', $bundle) }}" onsubmit="return confirm('Hapus bundle {{ addslashes($bundle->name) }}?')">
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
            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Belum ada paket bundle yang didaftarkan.
        </div>
        @endforelse
    </div>

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
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl">
                    
                    <form id="bundleForm" method="POST" action="{{ old('_method') == 'PUT' ? route('admin.bundles.update', old('id', 0)) : route('admin.bundles.store') }}">
                        @csrf
                        <div id="methodInput">
                            @if(old('_method') == 'PUT') @method('PUT') @endif
                        </div>
                        <input type="hidden" name="id" value="{{ old('id') }}">
                        
                        <!-- Header Modal -->
                        <div class="bg-white px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900" id="formTitle">Tambah Bundle Baru</h3>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        
                        <!-- Body Modal -->
                        <div class="px-6 py-6 space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bundle <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="Contoh: Paket Hemat Keluarga"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('name') border-red-400 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0"
                                       placeholder="100000"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('price') border-red-400 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                                <textarea id="description" name="description" rows="3"
                                          placeholder="Deskripsi bundle (opsional)..."
                                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('description') border-red-400 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="bg-gray-50/80 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 sm:px-6">
                            <button type="button" @click="showModal = false" class="bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-medium px-5 py-2.5 rounded-xl transition-colors shadow-sm">
                                Batal
                            </button>
                            <button type="submit" id="submitBtn" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors shadow-sm focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 flex items-center gap-2">
                                Simpan Bundle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
