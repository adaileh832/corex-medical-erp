<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الفاتورة | Invoice</title>
    <style>
        @page { size: A4; margin: 18mm; }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f4f6;
            color: #111827;
            padding: 24px;
        }

        .page {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }

        .meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }

        .meta-box {
            background: #f8fafc;
            padding: 14px;
            border-radius: 12px;
        }

        .meta-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .meta-value {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
            text-align: right;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            color: #475569;
        }

        .totals {
            margin-top: 24px;
            max-width: 380px;
            margin-inline-start: auto;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .actions {
            margin-top: 24px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            border: none;
            background: #2563eb;
            color: white;
            padding: 12px 16px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-secondary {
            background: #0f172a;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .page {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
                margin: 0;
                padding: 0;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="page">
    @include('invoices.partials.header')

    <div class="meta">
        <div class="meta-box">
            <div class="meta-label">رقم الفاتورة</div>
            <div class="meta-value">{{ $invoice->invoice_number }}</div>
        </div>

        <div class="meta-box">
            <div class="meta-label">التاريخ</div>
            <div class="meta-value">{{ optional($invoice->created_at)->format('Y-m-d H:i') }}</div>
        </div>

        <div class="meta-box">
            <div class="meta-label">المريض</div>
            <div class="meta-value">{{ $invoice->patient?->display_name ?? '-' }}</div>
        </div>

        <div class="meta-box">
            <div class="meta-label">طريقة الدفع</div>
            <div class="meta-value">{{ config('hospital.payment_methods.' . $invoice->payment_method, $invoice->payment_method ?? '-') }}</div>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>الإجراء</th>
            <th>السعر</th>
            <th>الكمية</th>
            <th>الإجمالي</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->procedure_name }}</td>
                <td>{{ number_format((float)$item->price, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format((float)$item->line_total, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <strong>المجموع الفرعي</strong>
            <span>{{ number_format((float)$invoice->subtotal, 2) }} {{ config('hospital.currency_symbol_ar') }}</span>
        </div>

        <div class="total-row">
            <strong>الخصم</strong>
            <span>{{ number_format((float)$invoice->discount, 2) }} {{ config('hospital.currency_symbol_ar') }}</span>
        </div>

        <div class="total-row">
            <strong>الإجمالي</strong>
            <span>{{ number_format((float)$invoice->total, 2) }} {{ config('hospital.currency_symbol_ar') }}</span>
        </div>

        <div class="total-row">
            <strong>المدفوع</strong>
            <span>{{ number_format((float)$invoice->paid_amount, 2) }} {{ config('hospital.currency_symbol_ar') }}</span>
        </div>

        <div class="total-row">
            <strong>المتبقي</strong>
            <span>{{ number_format((float)$invoice->balance, 2) }} {{ config('hospital.currency_symbol_ar') }}</span>
        </div>

        <div class="total-row" style="border-bottom:none;">
            <strong>الحالة</strong>
            <span>{{ $invoice->display_status }}</span>
        </div>
    </div>

    @if($invoice->notes)
        <div style="margin-top:24px;">
            <strong>ملاحظات</strong>
            <p style="line-height:1.8;">{{ $invoice->notes }}</p>
        </div>
    @endif

    <div class="actions">
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">رجوع / Back</a>
        <button class="btn" onclick="window.print()">طباعة A4 / Print</button>
    </div>
</div>
</body>
</html>
