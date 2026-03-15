<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف حساب الأطباء</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#111827}
        .container{max-width:1200px;margin:0 auto;padding:24px}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:20px}
        .btn{display:inline-block;text-decoration:none;border:none;background:#0f172a;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700}
        table{width:100%;border-collapse:collapse}
        th,td{padding:12px 10px;border-bottom:1px solid #e5e7eb;text-align:right;font-size:14px}
        th{background:#f8fafc;color:#475569}
        .view-link{display:inline-block;padding:8px 10px;border-radius:8px;background:#eff6ff;color:#1d4ed8;text-decoration:none;font-weight:700}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1 style="margin:0;">كشف حساب الأطباء / Doctor Statements</h1>
            <p style="margin:8px 0 0;color:#64748b;">إدارة المستحقات والدفعات الخاصة بالأطباء</p>
        </div>
        <a href="{{ url('/dashboard') }}" class="btn">الداشبورد / Dashboard</a>
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>الطبيب</th>
                <th>التخصص</th>
                <th>إجمالي المستحق</th>
                <th>إجمالي المدفوع</th>
                <th>المتبقي</th>
                <th>العمليات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($doctors as $doctor)
                <tr>
                    <td>{{ $doctor->id }}</td>
                    <td>{{ $doctor->display_name }}</td>
                    <td>{{ $doctor->specialty ?? '-' }}</td>
                    <td>{{ number_format((float)$doctor->total_due, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                    <td>{{ number_format((float)$doctor->total_paid, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                    <td>{{ number_format((float)$doctor->balance, 2) }} {{ config('hospital.currency_symbol_ar') }}</td>
                    <td>
                        <a href="{{ route('doctor-statements.show', $doctor) }}" class="view-link">عرض / View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">لا يوجد أطباء.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>