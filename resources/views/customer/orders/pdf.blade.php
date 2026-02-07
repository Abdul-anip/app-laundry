<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .company-info {
            font-size: 14px;
            color: #666;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-align: right;
        }
        .order-meta {
            margin-bottom: 30px;
            overflow: hidden;
        }
        .meta-left {
            float: left;
            width: 50%;
        }
        .meta-right {
            float: right;
            width: 40%;
            text-align: right;
        }
        .meta-label {
            font-weight: bold;
            font-size: 13px;
            color: #666;
        }
        .meta-value {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f5f7fa;
            color: #333;
            font-weight: bold;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #ddd;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .total-section {
            width: 100%;
            overflow: hidden;
            margin-top: 20px;
        }
        .total-table {
            float: right;
            width: 50%;
        }
        .total-row td {
            border: none;
            padding: 5px 12px;
        }
        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            border-top: 2px solid #ddd !important;
            padding-top: 10px !important;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-paid { background-color: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">VIP Laundry</div>
        <div class="company-info">
            Premium Laundry Service<br>
            Fast • Clean • Fragrant
        </div>
    </div>

    <div class="invoice-details">
        <div class="invoice-title">INVOICE</div>
        
        <div class="order-meta">
            <div class="meta-left">
                <div class="meta-label">BILLED TO:</div>
                <div class="meta-value">
                    <strong>{{ $order->customer_name }}</strong><br>
                    {{ $order->phone }}<br>
                    {{ $order->address }}
                </div>
            </div>
            <div class="meta-right">
                <div class="meta-label">ORDER NUMBER:</div>
                <div class="meta-value">#{{ $order->order_code }}</div>
                
                <div class="meta-label">DATE:</div>
                <div class="meta-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
                
                <div class="meta-label">STATUS:</div>
                <div class="meta-value">
                    @if($order->payment_status == 'paid')
                        <span class="badge badge-paid">PAID</span>
                    @else
                        <span class="badge badge-pending">UNPAID</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item / Service</th>
                <th>Type</th>
                <th>Qty / Weight</th>
                <th style="text-align: right;">Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @if($order->service)
                        {{ $order->service->name }}
                    @elseif($order->bundle)
                        {{ $order->bundle->name }}
                    @else
                        Custom Service
                    @endif
                </td>
                <td>
                    @if($order->service)
                        Service (Per KG)
                    @elseif($order->bundle)
                        Bundle Package
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($order->service)
                        {{ $order->weight_kg }} kg
                    @else
                        1 unit
                    @endif
                </td>
                <td style="text-align: right;">
                    @if($order->service)
                        Rp {{ number_format($order->service->price_per_kg, 0, ',', '.') }}
                    @elseif($order->bundle)
                        Rp {{ number_format($order->bundle->price, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td style="text-align: right;">
                    @if($order->service)
                        Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                    @else
                        Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        <table class="total-table">
            <tr class="total-row">
                <td style="text-align: right;">Subtotal:</td>
                <td style="text-align: right; width: 120px;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($order->pickup_fee > 0)
            <tr class="total-row">
                <td style="text-align: right;">Pickup Fee:</td>
                <td style="text-align: right;">Rp {{ number_format($order->pickup_fee, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($order->discount > 0)
            <tr class="total-row">
                <td style="text-align: right; color: #059669;">Discount:</td>
                <td style="text-align: right; color: #059669;">- Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td class="grand-total" style="text-align: right;">Total:</td>
                <td class="grand-total" style="text-align: right;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for choosing VIP Laundry! We appreciate your business.</p>
        <p>If you have any questions about this invoice, please contact us.</p>
    </div>
</body>
</html>
