<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة طبية</title>
    <style>
        @page { size: A4; margin: 14mm; }

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
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin: 24px 0 12px;
            color: #111827;
        }

        .meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .box {
            background: #f8fafc;
            padding: 14px;
            border-radius: 12px;
        }

        .box .label {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .box .value {
            color: #111827;
            font-weight: 700;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 10px;
            text-align: right;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            color: #475569;
        }

        .totals {
            margin-top: 24px;
            max-width: 420px;
            margin-inline-start: auto;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .note {
            margin-top: 26px;
            padding: 14px;
            border-radius: 12px;
            background: #fff7ed;
            color: #9a3412;
            font-size: 14px;
            line-height: 1.8;
        }

        .actions {
            margin-top: 26px;
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

        .btn-danger {
            background: #dc2626;
        }

        form { margin: 0; }

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

    @if (session('success'))
        <div style="background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;padding:12px 14px;border-radius:10px;margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="section-title">بيانات الفاتورة / Invoice Information</div>

    <div class="meta">
        <div class="box">
            <div class="label">رقم الفاتورة</div>
            <div class="value">{{ $invoice->invoice_number ?? '#' . $invoice->id }}</div>
        </div>

        <div class="box">
            <div class="label">تاريخ إنشاء الفاتورة</div>
            <div class="value">{{ optional($invoice->created_at)->format('Y-m-d H:i') ?? '-' }}</div>
        </div>

        <div class="box">
            <div class="label">طريقة الدفع</div>
            <div class="value">{{ config('hospital.payment_methods.' . $invoice->payment_method, $invoice->payment_method ?? '-') }}</div>
        </div>

        <div class="box">
            <div class="label">تاريخ آخر دفعة</div>
            <div class="value">{{ optional($invoice->payment_date)->format('Y-m-d H:i') ?? '-' }}</div>
        </div>
    </div>

    <div class="section-title">بيانات المريض / Patient Information</div>

    <div class="meta">
        <div class="box">
            <div class="label">اسم المريض</div>
            <div class="value">{{ $invoice->patient?->display_name ?? '-' }}</div>
        </div>

        <div class="box">
            <div class="label">رقم الهاتف</div>
            <div class="value">{{ $invoice->patient?->phone ?? '-' }}</div>
        </div>

        <div class="box">
            <div class="label">الجنس</div>
            <div class="value">{{ $invoice->patient?->gender ?? '-' }}</div>
        </div>

        <div class="box">
            <div class="label">البريد الإلكتروني</div>
            <div class="value">{{ $invoice->patient?->email ?? '-' }}</div>
        </div>
    </div>

    <div class="section-title">تفاصيل الخدمات المقدمة / Provided Services</div>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>الإجراء / Procedure</th>
            <th>السعر / Price</th>
            <th>الكمية / Qty</th>
            <th>الإجمالي / Total</th>
        </tr>
        </thead>
        <tbody>
        @forelse($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->procedure_name }}</td>
                <td>{{ number_format((float)$item->price, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format((float)$item->line_total, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">لا توجد إجراءات محفوظة داخل الفاتورة.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="section-title">الدفعات / Payments</div>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>المبلغ / Amount</th>
            <th>طريقة الدفع / Method</th>
            <th>تاريخ الدفع / Date</th>
            <th>ملاحظات / Notes</th>
            <th>العمليات / Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($invoice->payments as $index => $payment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ number_format((float)$payment->amount, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                <td>{{ config('hospital.payment_methods.' . $payment->payment_method, $payment->payment_method ?? '-') }}</td>
                <td>{{ optional($payment->payment_date)->format('Y-m-d H:i') ?? '-' }}</td>
                <td>{{ $payment->notes ?? '-' }}</td>
                <td>
                    <form method="POST" action="{{ route('payments.destroy', [$invoice, $payment]) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الدفعة؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding:8px 10px;font-size:13px;">حذف / Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">لا توجد دفعات مسجلة على هذه الفاتورة.</td>
            </tr>
        @endforelse
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
            <strong>المبلغ الإجمالي المستحق</strong>
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
        <div class="section-title">ملاحظات / Notes</div>
        <div class="box" style="margin-top:0;">
            {{ $invoice->notes }}
        </div>
    @endif

    <div class="note">
        الختم غير مضاف داخل تصميم الفاتورة، وسيتم وضعه بعد الطباعة يدويًا حسب طلبكم.
    </div>

    <div class="actions">
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">رجوع / Back</a>
        <a href="{{ route('payments.create', $invoice) }}" class="btn">إضافة دفعة / Add Payment</a>
        <button class="btn" onclick="window.print()">طباعة A4 / Print</button>
    </div>
</div>
</body>
</html>