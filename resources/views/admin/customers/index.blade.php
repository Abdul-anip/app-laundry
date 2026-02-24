@extends('layouts.admin')
@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
    <form method="GET" action="{{ route('admin.customers.index') }}">
        {{-- Row 1: Search --}}
        <div class="mb-3">
            <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / email / no HP..."
                   class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
        {{-- Row 2: Filter + Buttons --}}
        <div class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-600 mb-1">Filter</label>
                <select name="filter" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 outline-none">
                    <option value="">Semua</option>
                    <option value="has_orders" {{ request('filter') === 'has_orders' ? 'selected' : '' }}>Pernah Order</option>
                    <option value="no_orders"  {{ request('filter') === 'no_orders'  ? 'selected' : '' }}>Belum Order</option>
                </select>
            </div>
            <div class="flex gap-2 shrink-0">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.customers.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Customers Table / Cards --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <p class="text-sm text-gray-500">Menampilkan <span class="font-semibold text-gray-800">{{ $customers->total() }}</span> customer</p>
    </div>

    {{-- DESKTOP: Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="text-left px-5 py-3">Customer</th>
                    <th class="text-left px-5 py-3">No HP</th>
                    <th class="text-left px-5 py-3">Total Order</th>
                    <th class="text-left px-5 py-3">Total Belanja</th>
                    <th class="text-left px-5 py-3">Poin</th>
                    <th class="text-left px-5 py-3">Bergabung</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <p class="font-semibold text-gray-800">{{ $customer->name }}</p>
                        <p class="text-xs text-gray-400">{{ $customer->email }}</p>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $customer->phone ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ $customer->orders_count }}
                        </span>
                    </td>
                    <td class="px-5 py-3 font-medium text-gray-800">
                        Rp {{ number_format($customer->orders_sum_total_price ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            {{ $customer->points }} pts
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $customer->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.customers.show', $customer) }}"
                           class="text-primary-600 hover:text-primary-800 text-xs font-medium">Detail →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-sm">Belum ada customer.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE: Cards --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($customers as $customer)
        <a href="{{ route('admin.customers.show', $customer) }}" class="block px-4 py-4 hover:bg-gray-50 transition-colors">
            {{-- Row 1: Nama + Poin --}}
            <div class="flex items-start justify-between mb-1.5">
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $customer->name }}</p>
                    <p class="text-xs text-gray-400">{{ $customer->email }}</p>
                </div>
                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 shrink-0 ml-2">
                    {{ $customer->points }} pts
                </span>
            </div>
            {{-- Row 2: HP + Stats --}}
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500">{{ $customer->phone ?? '-' }}</p>
                <div class="flex items-center gap-2">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                        {{ $customer->orders_count }} order
                    </span>
                    <span class="text-xs font-semibold text-gray-700">
                        Rp {{ number_format($customer->orders_sum_total_price ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-1">Bergabung {{ $customer->created_at->format('d M Y') }}</p>
        </a>
        @empty
        <div class="px-5 py-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="text-sm">Belum ada customer.</p>
        </div>
        @endforelse
    </div>

    @if($customers->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $customers->links() }}</div>
    @endif
</div>

@endsection
