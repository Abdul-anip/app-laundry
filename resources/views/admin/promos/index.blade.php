@extends('layouts.admin')
@section('title', 'Promos')
@section('page-title', 'Promos')

@section('content')
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
        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg">Filter</button>
        <a href="{{ route('admin.promos.index') }}" class="text-gray-500 hover:text-gray-700 text-sm px-3 py-2">Reset</a>
    </form>

    <a href="{{ route('admin.promos.create') }}"
       class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Promo
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
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
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-mono font-bold text-gray-800">{{ $promo->code }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $promo->discount_type === 'percent' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                        {{ $promo->discount_type === 'percent' ? 'Persentase' : 'Fixed' }}
                    </span>
                </td>
                <td class="px-5 py-3 font-semibold text-gray-800">
                    {{ $promo->discount_type === 'percent' ? $promo->value . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.') }}
                </td>
                <td class="px-5 py-3 {{ $isExpired ? 'text-red-500' : 'text-gray-600' }}">
                    {{ $promo->expired_at ? $promo->expired_at->format('d M Y') : '∞ Tanpa batas' }}
                </td>
                <td class="px-5 py-3">
                    @if($isExpired)
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">Expired</span>
                    @elseif($promo->is_active)
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                    @else
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $promo->orders_count }}x</span>
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.promos.edit', $promo) }}" class="text-primary-600 hover:text-primary-800 text-xs font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.promos.destroy', $promo) }}" onsubmit="return confirm('Hapus promo {{ $promo->code }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">Belum ada promo.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
