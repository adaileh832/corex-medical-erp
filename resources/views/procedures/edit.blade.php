<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Procedure | تعديل الإجراء</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#1f2937}
        .container{max-width:900px;margin:0 auto;padding:24px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:24px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .full{grid-column:1 / -1}
        label{display:block;margin-bottom:8px;font-weight:700;font-size:14px}
        input,textarea,select{width:100%;box-sizing:border-box;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px}
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
            <h1 style="margin:0;">تعديل الإجراء / Edit Procedure</h1>
            <p style="margin:8px 0 0;color:#64748b;">تحديث بيانات الإجراء داخل CoreX</p>
        </div>

        <a href="{{ route('procedures.index') }}" class="btn btn-secondary">رجوع / Back</a>
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

        <form method="POST" action="{{ route('procedures.update', $procedure) }}">
            @csrf
            @method('PUT')

            <div class="grid">
                <div>
                    <label for="name">اسم الإجراء / Procedure Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $procedure->display_name) }}" required>
                </div>

                <div>
                    <label for="code">كود الإجراء / Procedure Code</label>
                    <input id="code" type="text" name="code" value="{{ old('code', $procedure->code) }}">
                </div>

                <div>
                    <label for="price">السعر / Price</label>
                    <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $procedure->price) }}" required>
                </div>

                <div>
                    <label for="duration_minutes">المدة بالدقائق / Duration Minutes</label>
                    <input id="duration_minutes" type="number" min="0" name="duration_minutes" value="{{ old('duration_minutes', $procedure->duration_minutes) }}">
                </div>

                <div class="full">
                    <label for="description">الوصف / Description</label>
                    <textarea id="description" name="description">{{ old('description', $procedure->description) }}</textarea>
                </div>

                <div class="full">
                    <label style="display:flex;align-items:center;gap:8px;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $procedure->display_status === 'active') ? 'checked' : '' }} style="width:auto;">
                        نشط / Active
                    </label>
                </div>
            </div>

            <div style="margin-top:20px;">
                <button type="submit" class="btn">حفظ التعديلات / Save Changes</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
