@extends('layouts.admin')
@section('title', 'Bundles')
@section('page-title', 'Bundles')

@section('content')
<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-gray-500">{{ $bundles->count() }} bundle terdaftar</p>
    <a href="{{ route('admin.bundles.create') }}"
       class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Bundle
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
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
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-semibold text-gray-800">{{ $bundle->name }}</td>
                <td class="px-5 py-3 text-gray-700">Rp {{ number_format($bundle->price, 0, ',', '.') }}</td>
                <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ $bundle->description ?? '-' }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $bundle->orders_count }}</span>
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.bundles.edit', $bundle) }}" class="text-primary-600 hover:text-primary-800 text-xs font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.bundles.destroy', $bundle) }}" onsubmit="return confirm('Hapus bundle?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">Belum ada bundle.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
