<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        .order-info {
            margin-bottom: 30px;
        }
        .order-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        table th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
        }
        .total-section {
            text-align: right;
            border-top: 2px solid #eee;
            padding-top: 20px;
        }
        .total-section p {
            margin: 5px 0;
            font-size: 16px;
        }
        .grand-total {
            font-size: 20px;
            font-weight: bold;
            color: #27ae60;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #7f8c8d;
        }
        .badge {
            background: #e1f5fe;
            color: #039be5;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    @php
        $settings = \App\Models\GeneralSetting::first();
        $supplier = \App\Models\Supplier::find($data['supplier']);
    @endphp
    <div class="container">
        <div class="header">
            @if($settings && $settings->logo)
                <img src="{{ asset($settings->logo) }}" alt="Logo" style="max-height: 60px; margin-bottom: 10px;">
            @endif
            <h1>Purchase Order</h1>
            <p>{{ $settings->name ?? 'Pharmacy Management System' }}</p>
        </div>

        <div class="order-info">
            <div style="float: left;">
                <p><strong>To:</strong></p>
                <p>{{ $supplier->name ?? 'N/A' }}</p>
                <p>{{ $supplier->phone ?? '' }}</p>
                <p>{{ $supplier->email ?? '' }}</p>
            </div>
            <div style="float: right; text-align: right;">
                <p><strong>Date:</strong> {{ date('d M, Y') }}</p>
                <p><strong>Status:</strong> <span class="badge">Pending</span></p>
            </div>
            <div style="clear: both;"></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['medicine'] as $index => $medicineId)
                    @php
                        $medicine = \App\Models\Medicine::find($medicineId);
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $medicine->name ?? 'Unknown Medicine' }}</strong><br>
                            <small>{{ $medicine->generic_name ?? '' }}</small>
                        </td>
                        <td style="text-align: center;">{{ $data['qty'][$index] }}</td>
                        <td style="text-align: right;">{{ number_format($data['price'][$index], 2) }}</td>
                        <td style="text-align: right;">{{ number_format($data['total'][$index], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <p><strong>Grand Total:</strong> <span class="grand-total">{{ number_format($data['grandTotal'], 2) }}</span></p>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>&copy; {{ date('Y') }} {{ $settings->name ?? 'Pharmacy Management' }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
