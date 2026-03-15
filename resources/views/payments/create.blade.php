<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة دفعة | Add Payment</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#111827}
        .container{max-width:900px;margin:0 auto;padding:24px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:24px}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .full{grid-column:1 / -1}
        label{display:block;margin-bottom:8px;font-weight:700;font-size:14px}
        input,select,textarea{width:100%;box-sizing:border-box;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px}
        textarea{min-height:90px;resize:vertical}
        .btn{display:inline-block;text-decoration:none;border:none;background:#2563eb;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700;cursor:pointer}
        .btn-secondary{background:#0f172a}
        .errors{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;padding:12px 14px;border-radius:10px;margin-bottom:16px}
        .summary{background:#f8fafc;border-radius:12px;padding:16px;margin-bottom:18px}
        .summary-row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #e5e7eb}
        .summary-row:last-child{border-bottom:none}
        @media (max-width:768px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1 style="margin:0;">إضافة دفعة / Add Payment</h1>
            <p style="margin:8px 0 0;color:#64748b;">إضافة دفعة على فاتورة موجودة</p>
        </div>

        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">رجوع / Back</a>
    </div>

    <div class="card">
        <div class="summary">
            <div class="summary-row">
                <strong>رقم الفاتورة</strong>
                <span>{{ $invoice->invoice_number ?? '#' . $invoice->id }}</span>
            </div>
            <div class="summary-row">
                <strong>المريض</strong>
                <span>{{ $invoice->patient?->display_name ?? '-' }}</span>
            </div>
            <div class="summary-row">
                <strong>الإجمالي</strong>
                <span>{{ number_format((float)$invoice->total, 2) }} {{ config('hospital.currency_symbol_ar') }}</span>
            </div>
            <div class="summary-row">
                <strong>المدفوع الحالي</strong>
                <span>{{ number_format((float)$invoice->paid_amount, 2) }} {{ config('hospital.currency_symbol_ar') }}</span>
            </div>
            <div class="summary-row">
                <strong>المتبقي</strong>
                <span>{{ number_format((float)$invoice->balance, 2) }} {{ config('hospital.currency_symbol_ar') }}</span>
            </div>
        </div>

        @if ($errors->any())
            <div class="errors">
                <strong>يوجد أخطاء / There are validation errors:</strong>
                <ul style="margin:8px 0 0;padding-inline-start:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('payments.store', $invoice) }}">
            @csrf

            <div class="grid">
                <div>
                    <label for="amount">مبلغ الدفعة / Payment Amount</label>
                    <input id="amount" type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount', $invoice->balance) }}" required>
                </div>

                <div>
                    <label for="payment_method">طريقة الدفع / Payment Method</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="">اختر</option>
                        @foreach($paymentMethods as $key => $label)
                            <option value="{{ $key }}" @selected(old('payment_method') == $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="payment_date">تاريخ الدفع / Payment Date</label>
                    <input id="payment_date" type="datetime-local" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="full">
                    <label for="notes">ملاحظات / Notes</label>
                    <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div style="margin-top:20px;">
                <button type="submit" class="btn">حفظ الدفعة / Save Payment</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>