<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian - Admin</title>
    <style>
        body { font-family: sans-serif; max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .filter-form { background: #f9fafb; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; }
        .filter-form form { display: flex; gap: 1rem; align-items: end; }
        .form-group { flex: 1; }
        .form-group label { display: block; margin-bottom: .5rem; font-weight: bold; }
        .form-group input { width: 100%; padding: .75rem; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: .75rem 1.5rem; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #2563eb; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: white; border: 2px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; }
        .stat-card.primary { border-color: #3b82f6; background: #eff6ff; }
        .stat-card.success { border-color: #10b981; background: #d1fae5; }
        .stat-card.warning { border-color: #f59e0b; background: #fef3c7; }
        .stat-card.info { border-color: #8b5cf6; background: #f3e8ff; }
        .stat-label { font-size: 0.875rem; color: #6b7280; text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem; }
        .stat-value { font-size: 2rem; font-weight: bold; color: #111827; }
        .stat-subtitle { font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>📊 Laporan Harian Laundry</h1>
            <p style="color: #666;">Statistik operasional harian</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" style="color: #666; text-decoration: none;">Kembali ke Dashboard</a>
    </div>

    <div class="filter-form">
        <form action="{{ route('admin.reports.daily') }}" method="GET">
            <div class="form-group">
                <label for="date">Pilih Tanggal</label>
                <input type="date" name="date" id="date" value="{{ $date }}" max="{{ now()->format('Y-m-d') }}">
            </div>
            <button type="submit" class="btn">Tampilkan Laporan</button>
        </form>
    </div>

    <h2 style="margin-bottom: 1rem;">Laporan tanggal <strong>{{ $stats['date'] }}</strong></h2>

    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ $stats['total_orders'] }}</div>
            <div class="stat-subtitle">Semua order hari ini</div>
        </div>

        <div class="stat-card success">
            <div class="stat-label">Order Online</div>
            <div class="stat-value">{{ $stats['total_online'] }}</div>
            <div class="stat-subtitle">Pesanan dari customer</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-label">Order Offline</div>
            <div class="stat-value">{{ $stats['total_offline'] }}</div>
            <div class="stat-subtitle">Walk-in customer</div>
        </div>

        <div class="stat-card info">
            <div class="stat-label">Total Berat</div>
            <div class="stat-value">{{ number_format($stats['total_weight'], 1) }} <span style="font-size: 1.5rem;">kg</span></div>
            <div class="stat-subtitle">Total laundry diproses</div>
        </div>

        <div class="stat-card" style="border-color: #059669; background: #ecfdf5; grid-column: span 2;">
            <div class="stat-label">💰 Total Pendapatan</div>
            <div class="stat-value" style="color: #059669;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            <div class="stat-subtitle">Revenue hari ini</div>
        </div>
    </div>

    @if($stats['total_orders'] == 0)
        <div style="background: #fef3c7; border: 1px solid #f59e0b; padding: 1rem; border-radius: 8px; text-align: center; color: #92400e;">
            ⚠️ Tidak ada transaksi pada tanggal ini.
        </div>
    @endif

</body>
</html>
