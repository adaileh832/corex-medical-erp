<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفواتير | Invoices</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#111827}
        .container{max-width:1200px;margin:0 auto;padding:24px}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:20px}
        .btn{display:inline-block;text-decoration:none;border:none;background:#2563eb;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700;cursor:pointer}
        .btn-secondary{background:#0f172a}
        table{width:100%;border-collapse:collapse;margin-top:10px}
        th,td{padding:12px 10px;border-bottom:1px solid #e5e7eb;text-align:right;font-size:14px;vertical-align:middle}
        th{background:#f8fafc;color:#475569}
        .alert{padding:12px 14px;border-radius:10px;margin-bottom:16px}
        .alert-success{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0}
        .actions{display:flex;gap:8px;flex-wrap:wrap}
        .actions a,.actions button{text-decoration:none;font-weight:700;font-size:13px;padding:8px 10px;border-radius:8px;border:none;cursor:pointer}
        .actions .view-link{background:#eff6ff;color:#1d4ed8}
        .actions .delete-btn{background:#fef2f2;color:#b91c1c}
        .badge{display:inline-block;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:700}
        .paid{background:#ecfdf5;color:#047857}
        .partial{background:#fff7ed;color:#b45309}
        .unpaid{background:#fef2f2;color:#b91c1c}
        form{margin:0}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1 style="margin:0;">الفواتير / Invoices</h1>
            <p style="margin:8px 0 0;color:#64748b;">إدارة فواتير المرضى داخل CoreX</p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ url('/dashboard') }}" class="btn btn-secondary">الداشبورد / Dashboard</a>
            <a href="{{ route('invoices.create') }}" class="btn">إضافة فاتورة / Add Invoice</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>رقم الفاتورة</th>
                <th>المريض</th>
                <th>الإجمالي</th>
                <th>المدفوع</th>
                <th>المتبقي</th>
                <th>طريقة الدفع</th>
                <th>الحالة</th>
                <th>العمليات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->patient?->display_name ?? '-' }}</td>
                    <td>{{ number_format((float)$invoice->total, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                    <td>{{ number_format((float)$invoice->paid_amount, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                    <td>{{ number_format((float)$invoice->balance, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                    <td>{{ config('hospital.payment_methods.' . $invoice->payment_method, $invoice->payment_method ?? '-') }}</td>
                    <td>
                        <span class="badge {{ $invoice->display_status }}">
                            {{ $invoice->display_status }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <a class="view-link" href="{{ route('invoices.show', $invoice) }}">عرض / View</a>

                            <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" onsubmit="return confirm('هل أنت متأكد من حذف الفاتورة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">حذف / Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;">لا توجد فواتير حتى الآن / No invoices found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top:16px;">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
</body>
</html>
