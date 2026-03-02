@extends('layouts.admin')

@section('title', 'Manajemen Staf Admin')
@section('page-title', 'Staf Admin')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <p class="text-sm text-gray-500">Kelola akun yang memiliki akses panel admin</p>
    </div>
    <a href="{{ route('admin.staff.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2.5 rounded-xl transition-colors text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Admin
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Tanggal Bergabung</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($staffs as $staff)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 font-medium text-gray-900">{{ $staff->name }}
                        @if($staff->email === 'admin@gmail.com')
                            <span class="ml-2 inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Super</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $staff->email }}</td>
                    <td class="px-5 py-4 text-gray-500">{{ $staff->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.staff.edit', $staff) }}" class="text-primary-600 hover:text-primary-800 font-medium">Edit</a>
                            @if($staff->email !== 'admin@gmail.com' && auth()->id() !== $staff->id)
                            <form method="POST" action="{{ route('admin.staff.destroy', $staff) }}" onsubmit="return confirm('Yakin ingin menghapus staf admin ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-8 text-center text-gray-500">Tidak ada data staf admin.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($staffs->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $staffs->links() }}
    </div>
    @endif
</div>
@endsection