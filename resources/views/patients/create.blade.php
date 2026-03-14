<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Patient | إضافة مريض</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#1f2937}
        .container{max-width:900px;margin:0 auto;padding:24px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:24px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .full{grid-column:1 / -1}
        label{display:block;margin-bottom:8px;font-weight:700;font-size:14px}
        input,select,textarea{width:100%;box-sizing:border-box;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px}
        textarea{min-height:110px;resize:vertical}
        .btn{display:inline-block;text-decoration:none;border:none;background:#2563eb;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700;cursor:pointer}
        .btn-secondary{background:#0f172a}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .errors{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;padding:12px 14px;border-radius:10px;margin-bottom:16px}
        @media (max-width: 768px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1 style="margin:0;">إضافة مريض / Add Patient</h1>
            <p style="margin:8px 0 0;color:#64748b;">إنشاء سجل مريض جديد داخل CoreX</p>
        </div>

        <a href="{{ route('patients.index') }}" class="btn btn-secondary">رجوع / Back</a>
    </div>

    <div class="card">
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

        <form method="POST" action="{{ route('patients.store') }}">
            @csrf

            <div class="grid">
                <div>
                    <label for="full_name">الاسم الكامل / Full Name</label>
                    <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" required>
                </div>

                <div>
                    <label for="phone">الهاتف / Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}">
                </div>

                <div>
                    <label for="email">البريد الإلكتروني / Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}">
                </div>

                <div>
                    <label for="gender">الجنس / Gender</label>
                    <select id="gender" name="gender">
                        <option value="">اختر / Select</option>
                        <option value="male" @selected(old('gender') === 'male')>Male / ذكر</option>
                        <option value="female" @selected(old('gender') === 'female')>Female / أنثى</option>
                    </select>
                </div>

                <div>
                    <label for="date_of_birth">تاريخ الميلاد / Date of Birth</label>
                    <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
                </div>

                <div>
                    <label for="address">العنوان / Address</label>
                    <input id="address" type="text" name="address" value="{{ old('address') }}">
                </div>

                <div class="full">
                    <label for="notes">ملاحظات / Notes</label>
                    <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div style="margin-top:20px;">
                <button type="submit" class="btn">حفظ المريض / Save Patient</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>