@extends('layouts.admin')

@section('title', 'Manajemen Kurir')
@section('page-title', 'Kurir')
@section('page-subtitle', 'Kelola data kurir dan status penugasan')

@section('content')
<div x-data="{ showCreateModal: {{ $errors->any() ? 'true' : 'false' }} }">
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            {{-- Statistik Singkat --}}
            <div class="flex gap-3">
                <div class="bg-green-50 border border-green-100 rounded-xl px-4 py-2 text-center">
                    <p class="text-xs text-green-600 font-medium">Idle</p>
                    <p class="text-lg font-bold text-green-700">{{ $couriers->getCollection()->where('status', 'idle')->count() }}</p>
                </div>
                <div class="bg-yellow-50 border border-yellow-100 rounded-xl px-4 py-2 text-center">
                    <p class="text-xs text-yellow-600 font-medium">Bertugas</p>
                    <p class="text-lg font-bold text-yellow-700">{{ $couriers->getCollection()->where('status', 'on_duty')->count() }}</p>
                </div>
            </div>
        </div>
        <button @click="showCreateModal = true"
           class="inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2.5 rounded-xl transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kurir
        </button>
    </div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3">Nama Kurir</th>
                    <th class="px-5 py-3">No. HP</th>
                    <th class="px-5 py-3 text-center">Poin</th>
                    <th class="px-5 py-3 text-center">Total Order</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($couriers as $courier)
                <tr class="hover:bg-gray-50 transition-colors" x-data="{ showEditModal: false }">
                    {{-- Nama --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm
                                {{ $courier->status === 'on_duty' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                {{ strtoupper(substr($courier->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $courier->name }}</p>
                                @if($courier->notes)
                                    <p class="text-xs text-gray-400">{{ Str::limit($courier->notes, 40) }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- No HP + WA --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-700">{{ $courier->phone }}</span>
                            @php
                                $phone = preg_replace('/[^0-9]/', '', $courier->phone);
                                if (str_starts_with($phone, '0')) $phone = '62' . substr($phone, 1);
                                $waUrl = 'https://wa.me/' . $phone;
                            @endphp
                            <a href="{{ $waUrl }}" target="_blank"
                               class="inline-flex items-center gap-1 bg-green-50 hover:bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded-lg transition-colors">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.136.562 4.14 1.535 5.876L.057 23.617a.5.5 0 00.609.61l5.957-1.51A11.943 11.943 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.927a9.912 9.912 0 01-5.031-1.371l-.361-.216-3.738.947.994-3.612-.235-.373A9.916 9.916 0 012.073 12C2.073 6.516 6.516 2.073 12 2.073S21.927 6.516 21.927 12 17.484 21.927 12 21.927z"/></svg>
                                WA
                            </a>
                        </div>
                    </td>

                    {{-- Poin --}}
                    <td class="px-5 py-4 text-center">
                        <div class="inline-flex items-center gap-1 bg-primary-50 text-primary-700 font-bold text-sm px-3 py-1 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ $courier->points }}
                        </div>
                    </td>

                    {{-- Total Order --}}
                    <td class="px-5 py-4 text-center">
                        <span class="text-gray-600 font-medium">{{ $courier->orders_count }}</span>
                    </td>

                    {{-- Status Badge & Active Orders --}}
                    <td class="px-5 py-4">
                        <div class="flex flex-col items-center gap-2">
                            @if($courier->status === 'on_duty')
                                <span class="inline-flex items-center gap-1.5 bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                    Sedang Ditugaskan
                                </span>
                                
                                {{-- Menampilkan list order yang sedang ditugaskan --}}
                                @if($courier->orders->isNotEmpty())
                                    <div class="flex flex-col gap-1 mt-1 w-full max-w-[160px]">
                                        @foreach($courier->orders as $activeOrder)
                                            <a href="{{ route('admin.orders.show', $activeOrder->id) }}"
                                               class="flex items-center justify-between bg-white border border-yellow-200 px-2 py-1 rounded text-xs hover:bg-yellow-50 transition-colors" title="Lihat Order">
                                                <span class="font-mono font-semibold text-gray-700">{{ $activeOrder->order_code }}</span>
                                                @php
                                                    $taskInitials = ['pickup'=>'JPT','delivery'=>'ATR','both'=>'J&A'];
                                                @endphp
                                                <span class="text-[10px] text-yellow-600 bg-yellow-50 px-1 rounded">{{ $taskInitials[$activeOrder->courier_task_type] ?? '-' }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Idle
                                </span>
                            @endif
                        </div>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($courier->status === 'on_duty')
                                <form method="POST" action="{{ route('admin.couriers.complete_task', $courier) }}"
                                      onsubmit="return confirm('Tandai tugas kurir {{ $courier->name }} selesai? Poin akan bertambah +1.')">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Selesai
                                    </button>
                                </form>
                            @endif
                            <button type="button" @click="showEditModal = true"
                               class="text-primary-600 hover:text-primary-800 font-medium text-sm">Edit</button>
                            
                            <!-- Modal Edit Kurir (Rendered inside the TD but positioned fixed) -->
                            <div x-show="showEditModal" x-cloak class="relative z-50 text-left" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div x-show="showEditModal"
                                     x-transition:enter="ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
                              
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                  <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                    <div x-show="showEditModal"
                                         @click.away="showEditModal = false"
                                         x-transition:enter="ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave="ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl">
                                         
                                        <div class="bg-primary-600 px-6 py-4">
                                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit Data Kurir
                                            </h3>
                                        </div>

                                        <div class="px-6 py-5">
                                            <form method="POST" action="{{ route('admin.couriers.update', $courier) }}" class="space-y-4">
                                                @csrf
                                                @method('PUT')
                                                {{-- Nama --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kurir <span class="text-red-500">*</span></label>
                                                    <input type="text" name="name" value="{{ old('name', $courier->name) }}" required
                                                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-400 @enderror">
                                                </div>

                                                {{-- No HP --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. HP / WhatsApp <span class="text-red-500">*</span></label>
                                                    <input type="text" name="phone" value="{{ old('phone', $courier->phone) }}" required
                                                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('phone') border-red-400 @enderror">
                                                </div>

                                                {{-- Catatan --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan <span class="text-gray-400 text-xs">(opsional)</span></label>
                                                    <textarea name="notes" rows="3"
                                                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none @error('notes') border-red-400 @enderror">{{ old('notes', $courier->notes) }}</textarea>
                                                </div>

                                                {{-- Actions --}}
                                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-5">
                                                    <button type="button" @click="showEditModal = false"
                                                       class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                            class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl transition-colors">
                                                        Simpan Perubahan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                            </div>

                            @if($courier->status !== 'on_duty')
                            <form method="POST" action="{{ route('admin.couriers.destroy', $courier) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus kurir {{ $courier->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-sm">Belum ada data kurir.</p>
                            <button @click="showCreateModal = true" class="text-primary-600 hover:text-primary-800 text-sm font-medium">+ Tambah kurir pertama</button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($couriers->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $couriers->links() }}
    </div>
    @endif
</div>

    <!-- Modal Tambah Kurir -->
    <div x-show="showCreateModal" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div x-show="showCreateModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
      
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div x-show="showCreateModal"
                 @click.away="showCreateModal = false"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl">
                 
                <div class="bg-primary-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Kurir Baru
                    </h3>
                </div>

                <div class="px-6 py-5">
                    <form method="POST" action="{{ route('admin.couriers.store') }}" class="space-y-4">
                        @csrf
                        {{-- Nama --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kurir <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   placeholder="Contoh: Budi Santoso"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-400 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">No. HP / WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                   placeholder="Contoh: 08123456789"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('phone') border-red-400 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan <span class="text-gray-400 text-xs">(opsional)</span></label>
                            <textarea name="notes" rows="3" placeholder="Catatan tambahan tentang kurir ini..."
                                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none @error('notes') border-red-400 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-5">
                            <button type="button" @click="showCreateModal = false"
                               class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl transition-colors">
                                Simpan Kurir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
