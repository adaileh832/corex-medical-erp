<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details | تفاصيل المريض</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#1f2937}
        .container{max-width:900px;margin:0 auto;padding:24px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:24px}
        .row{display:grid;grid-template-columns:220px 1fr;gap:12px;padding:12px 0;border-bottom:1px solid #e5e7eb}
        .label{font-weight:700;color:#475569}
        .btn{display:inline-block;text-decoration:none;border:none;background:#0f172a;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        @media (max-width: 768px){.row{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1 style="margin:0;">تفاصيل المريض / Patient Details</h1>
            <p style="margin:8px 0 0;color:#64748b;">عرض بيانات المريض داخل CoreX</p>
        </div>

        <a href="{{ route('patients.index') }}" class="btn">رجوع / Back</a>
    </div>

    <div class="card">
        <div class="row">
            <div class="label">الاسم الكامل / Full Name</div>
            <div>{{ $patient->display_name }}</div>
        </div>

        <div class="row">
            <div class="label">الهاتف / Phone</div>
            <div>{{ $patient->phone ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="label">البريد الإلكتروني / Email</div>
            <div>{{ $patient->email ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="label">الجنس / Gender</div>
            <div>{{ $patient->gender ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="label">تاريخ الميلاد / Date of Birth</div>
            <div>{{ optional($patient->date_of_birth)->format('Y-m-d') ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="label">العنوان / Address</div>
            <div>{{ $patient->address ?? '-' }}</div>
        </div>

        <div class="row" style="border-bottom:none;">
            <div class="label">ملاحظات / Notes</div>
            <div>{{ $patient->notes ?? '-' }}</div>
        </div>
    </div>
</div>
</body>
</html>