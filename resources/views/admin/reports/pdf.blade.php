<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian — {{ $date->format('d M Y') }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 12px; color: #111; margin: 0; padding: 20px; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        p  { margin: 2px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        thead th { background: #1d4ed8; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; text-transform: uppercase; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        tfoot td { padding: 8px 10px; font-weight: bold; border-top: 2px solid #1d4ed8; background: #eff6ff; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; }
        .badge-finished { background: #d1fae5; color: #065f46; }
        .badge-delivered { background: #ccfbf1; color: #134e4a; }
        .badge-process { background: #e0e7ff; color: #3730a3; }
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin: 16px 0; }
        .summary-box { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; text-align: center; }
        .summary-box .value { font-size: 18px; font-weight: 700; color: #1d4ed8; }
        .summary-box .label { font-size: 10px; color: #6b7280; margin-top: 2px; }
        .footer { margin-top: 24px; text-align: center; color: #9ca3af; font-size: 10px; }
    </style>
</head>
<body>
    <h1>Laporan Harian — VIP Laundry</h1>
    <p>Tanggal: {{ $date->format('d F Y') }}</p>
    <p>Dicetak: {{ now()->format('d M Y H:i') }}</p>

    <div class="summary-grid">
        <div class="summary-box">
            <div class="value">{{ $summary['total_orders'] }}</div>
            <div class="label">Total Order</div>
        </div>
        <div class="summary-box">
            <div class="value">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
            <div class="label">Total Pendapatan</div>
        </div>
        <div class="summary-box">
            <div class="value">{{ number_format($summary['total_weight'], 1) }} Kg</div>
            <div class="label">Total Berat</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Order</th>
                <th>Customer</th>
                <th>Layanan</th>
                <th>Berat</th>
                <th>Status</th>
                <th>Total</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $i => $order)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-family: monospace; font-weight: 600;">{{ $order->order_code }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->service?->name ?? $order->bundle?->name ?? '-' }}</td>
                <td>{{ $order->weight_kg > 0 ? $order->weight_kg . ' Kg' : '-' }}</td>
                <td>
                    <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </td>
                <td style="text-align: right;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>{{ $order->created_at->format('H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align: center; color: #9ca3af; padding: 20px;">Tidak ada order.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">Total ({{ $summary['total_orders'] }} order)</td>
                <td style="text-align: right;">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        VIP Laundry &bull; Laporan Harian {{ $date->format('d M Y') }} &bull; Digenerate otomatis
    </div>
</body>
</html>
