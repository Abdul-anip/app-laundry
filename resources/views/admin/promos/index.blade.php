<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Promos - Admin</title>
    <style>
        body { font-family: sans-serif; max-width: 1000px; margin: 2rem auto; padding: 0 1rem; }
        .table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .table th, .table td { border: 1px solid #ddd; padding: 0.75rem; text-align: left; }
        .table th { background-color: #f3f4f6; }
        .btn { padding: 0.5rem 1rem; background: #3b82f6; color: white; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 0.875rem; }
        .btn:hover { background: #2563eb; }
        .btn-danger { background: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
        .btn-success { background: #10b981; }
        .btn-success:hover { background: #059669; }
        .badge { padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: bold; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Daftar Promo</h1>
        <div>
            <a href="{{ route('admin.dashboard') }}" style="color: #666; text-decoration: none; margin-right: 1rem;">Kembali ke Dashboard</a>
            <a href="{{ route('admin.promos.create') }}" class="btn btn-success">+ Tambah Promo</a>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #dcfce7; color: #15803d; padding: 1rem; border-radius: 4px; margin: 1rem 0;">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Tipe Diskon</th>
                <th>Nilai</th>
                <th>Expired At</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($promos as $promo)
            <tr>
                <td style="font-weight: bold;">{{ $promo->code }}</td>
                <td>{{ $promo->discount_type == 'percent' ? 'Persentase' : 'Nominal Tetap' }}</td>
                <td>
                    {{ $promo->discount_type == 'percent' ? $promo->value . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.') }}
                </td>
                <td>
                    {{ $promo->expired_at ? $promo->expired_at->format('d M Y') : 'Selamanya' }}
                </td>
                <td>
                    @if($promo->is_active && ($promo->expired_at == null || $promo->expired_at >= now()))
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-danger">Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.promos.edit', $promo) }}" class="btn">Edit</a>
                    <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus promo ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Belum ada promo.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $promos->links() }}
    </div>
</body>
</html>
