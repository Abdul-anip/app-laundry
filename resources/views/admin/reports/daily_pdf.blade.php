<!DOCTYPE html>
<html>
<head>
    <title>Daily Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        
        .summary { display: table; width: 100%; margin-bottom: 20px; background: #f9fafb; padding: 15px; border-radius: 5px; }
        .summary-item { display: table-cell; text-align: center; width: 33.33%; border-right: 1px solid #ddd; }
        .summary-item:last-child { border-right: none; }
        .summary-label { display: block; font-size: 12px; color: #666; text-transform: uppercase; margin-bottom: 5px; }
        .summary-value { display: block; font-size: 18px; font-weight: bold; color: #333; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f3f4f6; color: #333; text-transform: uppercase; font-size: 12px; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .bg-green { background: #d1fae5; color: #065f46; } /* Online */
        .bg-gray { background: #f3f4f6; color: #1f2937; } /* Offline */
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>VIP Laundry - Daily Report</h1>
        <p>Date: {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Orders</span>
            <span class="summary-value">{{ $stats['total_orders'] }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Weight</span>
            <span class="summary-value">{{ $stats['total_weight'] }} kg</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Revenue</span>
            <span class="summary-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Breakdown -->
    <div style="margin-bottom: 20px; font-size: 12px; color: #666; text-align: center;">
        Online Orders: <strong>{{ $stats['online_orders'] }}</strong> | 
        Offline Orders: <strong>{{ $stats['offline_orders'] }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order Code</th>
                <th>Time</th>
                <th>Customer</th>
                <th>Service</th>
                <th class="text-right">Weight</th>
                <th class="text-center">Source</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td><strong>{{ $order->order_code }}</strong></td>
                <td>{{ $order->created_at->format('H:i') }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>
                    {{ $order->service ? $order->service->name : ($order->bundle ? $order->bundle->name : '-') }}
                </td>
                <td class="text-right">{{ floatval($order->weight_kg) }} kg</td>
                <td class="text-center">
                    <span class="badge {{ $order->order_source == 'online' ? 'bg-green' : 'bg-gray' }}">
                        {{ strtoupper($order->order_source) }}
                    </span>
                </td>
                <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #999;">No orders found for this date.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">Grand Total</th>
                <th class="text-right">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y H:i') }} by {{ auth()->user()->name }}
    </div>
</body>
</html>
