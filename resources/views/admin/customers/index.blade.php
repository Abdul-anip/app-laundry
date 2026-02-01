<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelanggan - Admin</title>
    <style>
        body { font-family: sans-serif; max-width: 1400px; margin: 2rem auto; padding: 0 1rem; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th { background: #3b82f6; color: white; padding: 1rem; text-align: left; font-weight: 600; }
        td { padding: 1rem; border-bottom: 1px solid #e5e7eb; }
        tr:hover { background: #f9fafb; }
        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 600; }
        .badge-registered { background: #dbeafe; color: #1e40af; }
        .badge-offline { background: #fef3c7; color: #92400e; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1rem; border-radius: 8px; border: 2px solid #e5e7eb; }
        .stat-label { font-size: 0.875rem; color: #6b7280; font-weight: 600; }
        .stat-value { font-size: 1.5rem; font-weight: bold; color: #111827; margin-top: 0.5rem; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>👥 Riwayat Pelanggan</h1>
            <p style="color: #666;">Daftar semua pelanggan dan statistik transaksi</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" style="color: #666; text-decoration: none;">Kembali ke Dashboard</a>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Total Pelanggan</div>
            <div class="stat-value">{{ $customers->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Akun Terdaftar</div>
            <div class="stat-value">{{ $customers->where('type', 'registered')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Walk-in Only</div>
            <div class="stat-value">{{ $customers->where('type', 'offline')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Order</div>
            <div class="stat-value">{{ $customers->sum('total_orders') }}</div>
        </div>
    </div>

    @if($customers->count() > 0)
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Tipe</th>
                <th>Total Order</th>
                <th>Total Berat (kg)</th>
                <th>Total Transaksi</th>
                <th>Terakhir Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $customer)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $customer['customer_name'] }}</strong></td>
                <td>
                    @if($customer['type'] == 'registered')
                        <span class="badge badge-registered">👤 Terdaftar</span>
                    @else
                        <span class="badge badge-offline">🚶 Walk-in</span>
                    @endif
                </td>
                <td>{{ $customer['total_orders'] }} order</td>
                <td>{{ number_format($customer['total_weight'], 1) }} kg</td>
                <td>Rp {{ number_format($customer['total_revenue'], 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($customer['last_order'])->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="background: #fef3c7; border: 1px solid #f59e0b; padding: 2rem; border-radius: 8px; text-align: center; color: #92400e;">
        ⚠️ Belum ada data pelanggan.
    </div>
    @endif

</body>
</html>
