@extends('layouts.admin')

@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Kelola semua order masuk')

@section('content')

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
    <form method="GET" action="{{ route('admin.orders.index') }}">

        {{-- Row 1: Search (full width) --}}
        <div class="mb-3">
            <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Kode order / nama customer..."
                   class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
        </div>

        {{-- Row 2: Status + Sumber (2 kolom) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">

            {{-- Status: Custom dropdown checkbox (Alpine.js) --}}
            <div x-data="{
                    open: false,
                    selected: {{ json_encode((array) request('status', [])) }},
                    statuses: ['pending','pickup','process','finished','delivered','completed'],
                    labels: { pending:'Pending', pickup:'Pickup', process:'Process', finished:'Finished', delivered:'Delivered', completed:'Completed' },
                    colors: { pending:'bg-yellow-100 text-yellow-700', pickup:'bg-blue-100 text-blue-700', process:'bg-indigo-100 text-indigo-700', finished:'bg-green-100 text-green-700', delivered:'bg-teal-100 text-teal-700', completed:'bg-gray-100 text-gray-600' },
                    toggle(s) { this.selected.includes(s) ? this.selected = this.selected.filter(x => x !== s) : this.selected.push(s); }
                 }" @click.outside="open = false" class="relative">
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>

                {{-- Hidden inputs for form submission --}}
                <template x-for="s in selected" :key="s">
                    <input type="hidden" name="status[]" :value="s">
                </template>

                {{-- Trigger button --}}
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white hover:border-gray-300 transition-colors text-left">
                    <span class="truncate" :class="selected.length === 0 ? 'text-gray-400' : 'text-gray-800'">
                        <span x-show="selected.length === 0">Semua Status</span>
                        <span x-show="selected.length > 0" x-text="selected.length + ' dipilih'"></span>
                    </span>
                    <svg class="w-4 h-4 text-gray-400 shrink-0 ml-1 transition-transform" :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown --}}
                <div x-show="open" x-cloak x-transition
                     class="absolute z-50 top-full left-0 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                    <template x-for="s in statuses" :key="s">
                        <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" class="rounded border-gray-300 text-primary-600"
                                   :checked="selected.includes(s)"
                                   @change="toggle(s)">
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full" :class="colors[s]" x-text="labels[s]"></span>
                        </label>
                    </template>
                    {{-- Clear button --}}
                    <div class="border-t border-gray-100 px-4 py-2" x-show="selected.length > 0">
                        <button type="button" @click="selected = []"
                                class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                            Hapus pilihan
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sumber</label>
                <select name="source" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 outline-none">
                    <option value="">Semua</option>
                    <option value="online"  {{ request('source') === 'online'  ? 'selected' : '' }}>Online</option>
                    <option value="offline" {{ request('source') === 'offline' ? 'selected' : '' }}>Offline</option>
                </select>
            </div>
        </div>

        {{-- Row 3: Tanggal + Buttons --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_until" value="{{ request('date_until') }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
        </div>

        {{-- Row 4: Buttons --}}
        <div class="flex gap-2 mt-3">
            <button type="submit" class="flex-1 sm:flex-none bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.orders.index') }}" class="flex-1 sm:flex-none text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                Reset
            </a>
        </div>
    </form>
</div>


{{-- Orders Table/Cards --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <p class="text-sm text-gray-500">Menampilkan <span class="font-semibold text-gray-800">{{ $orders->total() }}</span> order</p>
    </div>

    @php
        $statusBadges = [
            'pending'   => 'bg-yellow-100 text-yellow-700',
            'pickup'    => 'bg-blue-100 text-blue-700',
            'process'   => 'bg-indigo-100 text-indigo-700',
            'finished'  => 'bg-green-100 text-green-700',
            'delivered' => 'bg-teal-100 text-teal-700',
            'completed' => 'bg-gray-100 text-gray-600',
        ];
    @endphp

    {{-- ===== DESKTOP: TABLE (md ke atas) ===== --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="text-left px-5 py-3">Kode</th>
                    <th class="text-left px-5 py-3">Customer</th>
                    <th class="text-left px-5 py-3">Layanan</th>
                    <th class="text-left px-5 py-3">Sumber</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-right px-5 py-3">Total</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="font-mono text-primary-600 hover:text-primary-800 font-semibold text-xs">
                            {{ $order->order_code }}
                        </a>
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800">{{ $order->customer_name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->phone }}</p>
                    </td>
                    <td class="px-5 py-3 text-gray-600">
                        {{ $order->service?->name ?? $order->bundle?->name ?? '-' }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $order->order_source === 'online' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($order->order_source) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $statusBadges[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-800">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-800 text-xs font-medium">
                            Detail →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <p class="text-sm">Tidak ada order ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== MOBILE: CARDS (di bawah md) ===== --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($orders as $order)
        <a href="{{ route('admin.orders.show', $order) }}" class="block px-4 py-4 hover:bg-gray-50 transition-colors">
            {{-- Row 1: Kode + Status --}}
            <div class="flex items-center justify-between mb-2">
                <span class="font-mono text-primary-600 font-bold text-xs">{{ $order->order_code }}</span>
                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $statusBadges[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            {{-- Row 2: Nama + Telepon --}}
            <div class="flex items-center justify-between mb-1">
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $order->customer_name }}</p>
                    <p class="text-xs text-gray-400">{{ $order->phone }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $order->order_source === 'online' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($order->order_source) }}
                    </span>
                </div>
            </div>
            {{-- Row 3: Layanan + Tanggal --}}
            <div class="flex items-center justify-between mt-1">
                <p class="text-xs text-gray-500">{{ $order->service?->name ?? $order->bundle?->name ?? '-' }}</p>
                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</p>
            </div>
        </a>
        @empty
        <div class="px-5 py-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <p class="text-sm">Tidak ada order ditemukan.</p>
        </div>
        @endforelse
    </div>

    @if($orders->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection

