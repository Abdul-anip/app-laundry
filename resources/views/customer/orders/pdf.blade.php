<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155; /* Slate 700 */
            line-height: 1.5;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }
        .header-bg {
            background-color: #1e293b; /* Slate 800 - Navy */
            color: #ffffff;
            padding: 40px 40px;
            overflow: hidden;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .logo span {
            color: #f59e0b; /* Amber 500 - Gold */
        }
        .company-subtitle {
            font-size: 12px;
            color: #94a3b8; /* Slate 400 */
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: 300;
            text-align: right;
            color: #ffffff;
            margin: 0;
        }
        .invoice-number {
            font-size: 14px;
            text-align: right;
            color: #94a3b8;
            margin-top: 5px;
        }
        .content {
            padding: 40px;
        }
        .box-container {
            margin-bottom: 40px;
            overflow: hidden;
        }
        .box {
            float: left;
            width: 45%;
        }
        .box-right {
            float: right;
            width: 45%;
            text-align: right;
        }
        .label {
            font-size: 10px;
            color: #64748b; /* Slate 500 */
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .value {
            font-size: 15px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 2px;
        }
        .value-light {
            font-size: 14px;
            color: #334155;
            font-weight: normal;
        }
        
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f1f5f9; /* Slate 100 */
            color: #475569; /* Slate 600 */
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .total-section {
            width: 100%;
            overflow: hidden;
            margin-top: 10px;
        }
        .total-table {
            float: right;
            width: 50%;
        }
        .total-row td {
            border: none;
            padding: 8px 15px;
        }
        .grand-total {
            background-color: #1e293b;
            color: #ffffff !important;
            font-size: 16px;
            font-weight: bold;
            padding: 15px !important;
            border-radius: 4px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-paid { background-color: #dcfce7; color: #166534; } /* Green */
        .status-unpaid { background-color: #fee2e2; color: #991b1b; } /* Red */
        
        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
        .accent-bar {
            height: 5px;
            background-color: #f59e0b; /* Gold */
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="accent-bar"></div>
    
    <div class="header-bg">
        <table width="100%">
            <tr>
                <td style="border:none; padding:0; vertical-align:top;">
                    <div class="logo">VIP <span>LAUNDRY</span></div>
                    <div class="company-subtitle">Premium Care for Your Best Wear</div>
                </td>
                <td style="border:none; padding:0; vertical-align:top;" align="right">
                    <h1 class="invoice-title">INVOICE</h1>
                    <div class="invoice-number">#{{ $order->order_code }}</div>
                    <div style="margin-top: 10px;">
                        @if($order->payment_status == 'paid')
                            <span class="status-badge status-paid" style="border: 1px solid #ffffff;">PAID</span>
                        @else
                            <span class="status-badge status-unpaid" style="border: 1px solid #ffffff;">UNPAID</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <div class="box-container">
            <div class="box">
                <div class="label">BILLED TO</div>
                <div class="value">{{ $order->customer_name }}</div>
                <div class="value-light">{{ $order->phone }}</div>
                <div class="value-light" style="margin-top:5px; max-width: 80%;">{{ $order->address }}</div>
            </div>
            
            <div class="box-right">
                <div class="label">DATE ISSUED</div>
                <div class="value">{{ $order->created_at->format('d M Y') }}</div>
                <div class="value-light">{{ $order->created_at->format('H:i') }} WIB</div>
                
                <div class="label" style="margin-top: 15px;">SERVICE TYPE</div>
                <div class="value">
                    @if($order->service) Regular Service
                    @elseif($order->bundle) Bundle Package
                    @else Custom Order @endif
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="40%">DESCRIPTION</th>
                    <th width="20%" class="text-center">QTY / WEIGHT</th>
                    <th width="20%" class="text-right">PRICE</th>
                    <th width="20%" class="text-right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight:bold; color: #1e293b;">
                            @if($order->service) {{ $order->service->name }}
                            @elseif($order->bundle) {{ $order->bundle->name }}
                            @else Custom Service @endif
                        </div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                            {{ $order->fabric_type ? 'Type: ' . $order->fabric_type : 'Standard Wash & Fold' }}
                        </div>
                    </td>
                    <td class="text-center">
                        @if($order->service) {{ floatval($order->weight_kg) }} Kg
                        @else 1 Unit @endif
                    </td>
                    <td class="text-right">
                        @if($order->service) Rp {{ number_format($order->service->price_per_kg, 0, ',', '.') }}
                        @elseif($order->bundle) Rp {{ number_format($order->bundle->price, 0, ',', '.') }}
                        @else - @endif
                    </td>
                    <td class="text-right" style="font-weight: bold;">
                        Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                
                <!-- Spacer Row to push totals down if needed, or minimal content -->
            </tbody>
        </table>

        <div class="total-section">
            <table class="total-table">
                <tr class="total-row">
                    <td class="text-right" style="color: #64748b;">Subtotal</td>
                    <td class="text-right" width="150" style="font-weight: bold;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                </tr>
                
                @if($order->pickup_fee > 0)
                <tr class="total-row">
                    <td class="text-right" style="color: #64748b;">Pickup Fee ({{ floatval($order->distance_km) }} km)</td>
                    <td class="text-right" style="font-weight: bold;">Rp {{ number_format($order->pickup_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                
                @if($order->discount > 0)
                <tr class="total-row">
                    <td class="text-right" style="color: #059669;">Discount {{ $order->promo ? '('.$order->promo->code.')' : '' }}</td>
                    <td class="text-right" style="color: #059669; font-weight: bold;">- Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                </tr>
                @endif
                
                <tr class="total-row">
                    <td colspan="2" style="padding: 10px 0;"></td>
                </tr>
                <tr class="total-row">
                    <td class="text-right" style="vertical-align: middle; font-weight: bold; font-size: 14px; padding-right: 15px;">GRAND TOTAL</td>
                    <td class="grand-total text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div style="clear: both;"></div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p style="margin-top: 5px;">
                <strong>VIP Laundry</strong> | Jl. Raya Laundry No. 123 | 0812-3456-7890 | support@viplaundry.com
            </p>
        </div>
    </div>
</body>
</html>
