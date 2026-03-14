<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procedure Details | تفاصيل الإجراء</title>
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
            <h1 style="margin:0;">تفاصيل الإجراء / Procedure Details</h1>
            <p style="margin:8px 0 0;color:#64748b;">عرض بيانات الإجراء داخل CoreX</p>
        </div>

        <a href="{{ route('procedures.index') }}" class="btn">رجوع / Back</a>
    </div>

    <div class="card">
        <div class="row">
            <div class="label">الاسم / Name</div>
            <div>{{ $procedure->display_name }}</div>
        </div>

        <div class="row">
            <div class="label">الكود / Code</div>
            <div>{{ $procedure->code ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="label">السعر / Price</div>
            <div>{{ number_format((float) $procedure->price, 2) }}</div>
        </div>

        <div class="row">
            <div class="label">المدة / Duration</div>
            <div>{{ $procedure->duration_minutes ?? 0 }} min</div>
        </div>

        <div class="row">
            <div class="label">الحالة / Status</div>
            <div>{{ $procedure->display_status }}</div>
        </div>

        <div class="row" style="border-bottom:none;">
            <div class="label">الوصف / Description</div>
            <div>{{ $procedure->description ?? '-' }}</div>
        </div>
    </div>
</div>
</body>
</html>
