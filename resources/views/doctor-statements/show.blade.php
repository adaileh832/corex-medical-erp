<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف حساب الطبيب</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#111827}
        .container{max-width:1200px;margin:0 auto;padding:24px}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:20px}
        .btn{display:inline-block;text-decoration:none;border:none;background:#2563eb;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700}
        .btn-secondary{background:#0f172a}
        .btn-danger{background:#dc2626}
        table{width:100%;border-collapse:collapse}
        th,td{padding:12px 10px;border-bottom:1px solid #e5e7eb;text-align:right;font-size:14px}
        th{background:#f8fafc;color:#475569}
        .section-title{font-size:18px;font-weight:700;margin:0 0 12px}
        .alert{padding:12px 14px;border-radius:10px;margin-bottom:16px;background:#ecfdf5;color:#047857;border:1px solid #a7f3d0}
        form{margin:0}
        @media (max-width: 900px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1 style="margin:0;">كشف حساب الطبيب / Doctor Statement</h1>
            <p style="margin:8px 0 0;color:#64748b;">{{ $doctor->display_name }}</p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('doctor-statements.index') }}" class="btn btn-secondary">رجوع / Back</a>
            <a href="{{ route('doctor-payments.create', $doctor) }}" class="btn">إضافة دفعة / Add Payment</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="grid">
        <div class="card">
            <div class="section-title">إجمالي المستحق</div>
            <div>{{ number_format((float)$doctor->total_due, 2) }} {{ config('hospital.currency_symbol_ar') }}</div>
        </div>
        <div class="card">
            <div class="section-title">إجمالي المدفوع</div>
            <div>{{ number_format((float)$doctor->total_paid, 2) }} {{ config('hospital.currency_symbol_ar') }}</div>
        </div>
        <div class="card">
            <div class="section-title">المتبقي</div>
            <div>{{ number_format((float)$doctor->balance, 2) }} {{ config('hospital.currency_symbol_ar') }}</div>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="section-title">إضافة مستحق جديد / Add Due Entry</div>

        <form method="POST" action="{{ route('doctor-statements.entries.store', $doctor) }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;">
                <input type="text" name="reference" placeholder="مرجع / Reference">
                <input type="text" name="description" placeholder="الوصف / Description" required>
                <input type="number" step="0.01" min="0.01" name="amount_due" placeholder="المبلغ المستحق / Due Amount" required>
                <input type="date" name="entry_date" value="{{ now()->format('Y-m-d') }}" required>
            </div>

            <div style="margin-top:12px;">
                <textarea name="notes" placeholder="ملاحظات / Notes" style="width:100%;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px;min-height:80px;"></textarea>
            </div>

            <div style="margin-top:12px;">
                <button type="submit" class="btn">حفظ المستحق / Save Due Entry</button>
            </div>
        </form>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="section-title">المستحقات / Due Entries</div>

        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>المرجع</th>
                <th>الوصف</th>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th>الملاحظات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($doctor->ledgers as $entry)
                <tr>
                    <td>{{ $entry->id }}</td>
                    <td>{{ $entry->reference ?? '-' }}</td>
                    <td>{{ $entry->description ?? '-' }}</td>
                    <td>{{ number_format((float)$entry->amount_due, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                    <td>{{ optional($entry->entry_date)->format('Y-m-d') ?? '-' }}</td>
                    <td>{{ $entry->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">لا توجد مستحقات للطبيب.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="section-title">دفعات الطبيب / Doctor Payments</div>

        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>المبلغ</th>
                <th>طريقة الدفع</th>
                <th>تاريخ الدفع</th>
                <th>الملاحظات</th>
                <th>العمليات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($doctor->payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ number_format((float)$payment->amount, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                    <td>{{ config('hospital.payment_methods.' . $payment->payment_method, $payment->payment_method ?? '-') }}</td>
                    <td>{{ optional($payment->payment_date)->format('Y-m-d H:i') ?? '-' }}</td>
                    <td>{{ $payment->notes ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('doctor-payments.destroy', $payment) }}" onsubmit="return confirm('هل أنت متأكد من حذف دفعة الطبيب؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding:8px 10px;font-size:13px;">حذف / Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">لا توجد دفعات للطبيب.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>