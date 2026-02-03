@extends('layouts.admin')

@section('title', 'Manage Promos - Admin')

@section('content')
    <div class="content-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="welcome-title">🎟️ Daftar Promo</h1>
            <p class="welcome-subtitle">Kelola kode promo dan diskon</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" style="color: #666; text-decoration: none; margin-right: 1rem;">Kembali ke Dashboard</a>
            <a href="{{ route('admin.promos.create') }}" style="background: #10b981; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 8px; font-weight: 600;">+ Tambah Promo</a>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #dcfce7; color: #15803d; padding: 1rem; border-radius: 8px; margin: 1rem 0; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f3f4f6;">
                    <th style="padding: 1rem; border-bottom: 1px solid #e5e7eb; text-align: left;">Code</th>
                    <th style="padding: 1rem; border-bottom: 1px solid #e5e7eb; text-align: left;">Tipe Diskon</th>
                    <th style="padding: 1rem; border-bottom: 1px solid #e5e7eb; text-align: left;">Nilai</th>
                    <th style="padding: 1rem; border-bottom: 1px solid #e5e7eb; text-align: left;">Expired At</th>
                    <th style="padding: 1rem; border-bottom: 1px solid #e5e7eb; text-align: left;">Status</th>
                    <th style="padding: 1rem; border-bottom: 1px solid #e5e7eb; text-align: left;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promos as $promo)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 1rem; font-weight: bold;">{{ $promo->code }}</td>
                    <td style="padding: 1rem;">{{ $promo->discount_type == 'percent' ? 'Persentase' : 'Nominal Tetap' }}</td>
                    <td style="padding: 1rem;">
                        {{ $promo->discount_type == 'percent' ? $promo->value . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.') }}
                    </td>
                    <td style="padding: 1rem;">
                        {{ $promo->expired_at ? $promo->expired_at->format('d M Y') : 'Selamanya' }}
                    </td>
                    <td style="padding: 1rem;">
                        @if($promo->is_active && ($promo->expired_at == null || $promo->expired_at >= now()))
                            <span style="padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: bold; background: #dcfce7; color: #15803d;">Aktif</span>
                        @else
                            <span style="padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: bold; background: #fee2e2; color: #991b1b;">Tidak Aktif</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <a href="{{ route('admin.promos.edit', $promo) }}" style="color: #3b82f6; text-decoration: none; font-weight: 500; margin-right: 0.5rem;">Edit</a>
                        <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #ef4444; font-weight: 500; cursor: pointer; padding: 0;" onclick="return confirm('Yakin ingin menghapus promo ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">⚠️ Belum ada promo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $promos->links() }}
    </div>
@endsection
