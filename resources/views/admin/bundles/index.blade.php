@extends('layouts.admin')

@section('title', 'Kelola Paket')

@section('content')
<div class="container">
    <div class="card card-lg">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.5rem 0;">📦 Kelola Paket Bundle</h1>
                <p style="color: var(--text-secondary); margin: 0;">Manajemen paket laundry flat price</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('admin.bundles.create') }}" class="btn btn-success">+ Tambah Paket</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Kembali</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        @if($bundles->count() > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Paket</th>
                        <th style="width: 180px;">Harga</th>
                        <th>Deskripsi</th>
                        <th style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bundles as $index => $bundle)
                    <tr>
                        <td style="text-align: center; font-weight: 600;">{{ $index + 1 }}</td>
                        <td><strong>{{ $bundle->name }}</strong></td>
                        <td>Rp {{ number_format($bundle->price, 0, ',', '.') }}</td>
                        <td style="color: var(--text-secondary);">{{ $bundle->description ?? '-' }}</td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.bundles.edit', $bundle) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Edit</a>
                                <form action="{{ route('admin.bundles.destroy', $bundle) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus paket ini?')" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-warning" style="text-align: center; padding: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
            <strong>Belum ada paket</strong>
            <p style="margin: 0.5rem 0 0 0; color: var(--text-secondary);">Tambahkan paket pertama Anda!</p>
        </div>
        @endif
    </div>
</div>
@endsection
