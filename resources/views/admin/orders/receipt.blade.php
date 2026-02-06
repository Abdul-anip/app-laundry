<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk - {{ $order->order_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: 0 auto;
            padding: 10mm;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            margin: 2px 0;
        }
        .section {
            margin-bottom: 15px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .label {
            font-weight: bold;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .total-row {
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #000;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 11px;
        }
        .notes {
            margin-top: 10px;
            padding: 5px;
            background: #f5f5f5;
            border-radius: 3px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>VIP LAUNDRY</h1>
        <p>Terima kasih atas kepercayaan Anda</p>
    </div>

    <div class="section">
        <div class="row">
            <span class="label">Order Code:</span>
            <span>{{ $order->order_code }}</span>
        </div>
        <div class="row">
            <span class="label">Tanggal:</span>
            <span>{{ $order->created_at->format('d M Y H:i') }}</span>
        </div>
        <div class="row">
            <span class="label">Customer:</span>
            <span>{{ $order->customer_name }}</span>
        </div>
        <div class="row">
            <span class="label">Telepon:</span>
            <span>{{ $order->phone }}</span>
        </div>
        <div class="row">
            <span class="label">Status:</span>
            <span>{{ strtoupper($order->status) }}</span>
        </div>
    </div>

    <div class="separator"></div>

    <div class="section">
        <div class="row">
            <span class="label">Layanan:</span>
            <span></span>
        </div>
        @if($order->service_id)
            <div class="row">
                <span>{{ $order->service->name }}</span>
                <span></span>
            </div>
            <div class="row">
                <span>{{ floatval($order->weight_kg) }} kg x Rp {{ number_format($order->service->price_per_kg, 0, ',', '.') }}</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
        @elseif($order->bundle_id)
            <div class="row">
                <span>{{ $order->bundle->name }}</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    @if($order->fabric_type)
    <div class="row">
        <span class="label">Tipe Kain:</span>
        <span>{{ $order->fabric_type }}</span>
    </div>
    @endif

    <div class="separator"></div>

    <div class="section">
        <div class="row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        @if($order->pickup_fee > 0)
        <div class="row">
            <span>Biaya Antar ({{ floatval($order->distance_km) }} km):</span>
            <span>Rp {{ number_format($order->pickup_fee, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($order->discount > 0)
        <div class="row">
            <span>Diskon {{ $order->promo ? '(' . $order->promo->code . ')' : '' }}:</span>
            <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
        </div>
        @endif
    </div>

    <div class="row total-row">
        <span>TOTAL BAYAR:</span>
        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>

    @if($order->description)
    <div class="notes">
        <strong>Catatan:</strong><br>
        {{ $order->description }}
    </div>
    @endif

    <div class="footer">
        <p>Struk ini dicetak pada {{ now()->format('d M Y H:i') }}</p>
        <p>-- Terima Kasih --</p>
    </div>
</body>
</html>
