<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail - Laundry App</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 3rem auto; padding: 0 1rem; text-align: center; }
        .success-icon { font-size: 4rem; color: #10b981; margin-bottom: 1rem; }
        .card { border: 1px solid #ddd; padding: 2rem; border-radius: 8px; text-align: left; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 0.5rem; }
        .total-row { display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem; border-top: 2px solid #eee; font-weight: bold; font-size: 1.2rem; }
        .btn { display: inline-block; padding: .75rem 1.5rem; background: #3b82f6; color: white; border: none; border-radius: 4px; text-decoration: none; margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="success-icon">✓</div>
    <h1>Pesanan Berhasil Dibuat!</h1>
    <p>Terima kasih telah mempercayakan laundry Anda kepada kami.</p>

    <div class="card">
        <h3 style="margin-top: 0;">Order Code: {{ $order->order_code }}</h3>
        <p style="color: #666; font-size: 0.9rem;">Status: <span style="color: #f59e0b; font-weight: bold;">{{ ucfirst($order->status) }}</span></p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 1rem 0;">

        <div class="detail-row">
            <span>Nama Pelanggan</span>
            <span>{{ $order->customer_name }}</span>
        </div>
        <div class="detail-row">
            <span>Tipe Pesanan</span>
            <span>
                @if($order->service_id)
                    {{ $order->service->name }} ({{ floatval($order->weight_kg) }} kg)
                @elseif($order->bundle_id)
                    {{ $order->bundle->name }}
                @endif
            </span>
        </div>
        <div class="detail-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="detail-row">
            <span>Ongkos Kirim ({{ floatval($order->distance_km) }} km)</span>
            <span>Rp {{ number_format($order->pickup_fee, 0, ',', '.') }}</span>
        </div>
        @if($order->discount > 0)
        <div class="detail-row" style="color: #10b981;">
            <span>Diskon Promo</span>
            <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
        </div>
        @endif
        
        <div class="total-row">
            <span>Total Bayar</span>
            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

    <a href="{{ route('customer.dashboard') }}" class="btn">Kembali ke Dashboard</a>
    <a href="{{ route('customer.orders.create') }}" class="btn" style="background: #fff; color: #3b82f6; border: 1px solid #3b82f6; margin-left: 10px;">Buat Pesanan Baru</a>
</body>
</html>
