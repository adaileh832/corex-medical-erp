<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procedures | الإجراءات</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#1f2937}
        .container{max-width:1200px;margin:0 auto;padding:24px}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:20px}
        .btn{display:inline-block;text-decoration:none;border:none;background:#2563eb;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700;cursor:pointer}
        .btn-secondary{background:#0f172a}
        table{width:100%;border-collapse:collapse;margin-top:10px}
        th,td{padding:12px 10px;border-bottom:1px solid #e5e7eb;text-align:start;font-size:14px;vertical-align:middle}
        th{background:#f8fafc;color:#475569}
        .alert{padding:12px 14px;border-radius:10px;margin-bottom:16px}
        .alert-success{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0}
        .actions{display:flex;gap:8px;flex-wrap:wrap}
        .actions a,.actions button{text-decoration:none;font-weight:700;font-size:13px;padding:8px 10px;border-radius:8px;border:none;cursor:pointer}
        .actions .view-link{background:#eff6ff;color:#1d4ed8}
        .actions .edit-link{background:#fff7ed;color:#b45309}
        .actions .delete-btn{background:#fef2f2;color:#b91c1c}
        .badge{display:inline-block;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:700}
        .active{background:#ecfdf5;color:#047857}
        .inactive{background:#fef2f2;color:#b91c1c}
        .empty{padding:20px;text-align:center;color:#64748b}
        form{margin:0}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1 style="margin:0;">الإجراءات / Procedures</h1>
            <p style="margin:8px 0 0;color:#64748b;">إدارة الخدمات والإجراءات الطبية داخل CoreX</p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ url('/dashboard') }}" class="btn btn-secondary">الداشبورد / Dashboard</a>
            <a href="{{ route('procedures.create') }}" class="btn">إضافة إجراء / Add Procedure</a>
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
                <th>الاسم / Name</th>
                <th>الكود / Code</th>
                <th>السعر / Price</th>
                <th>المدة / Duration</th>
                <th>الحالة / Status</th>
                <th>العمليات / Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($procedures as $procedure)
                <tr>
                    <td>{{ $procedure->id }}</td>
                    <td>{{ $procedure->display_name }}</td>
                    <td>{{ $procedure->code ?? '-' }}</td>
                    <td>{{ number_format((float) $procedure->price, 2) }}</td>
                    <td>{{ $procedure->duration_minutes ?? 0 }} min</td>
                    <td>
                        <span class="badge {{ $procedure->display_status === 'active' ? 'active' : 'inactive' }}">
                            {{ $procedure->display_status === 'active' ? 'نشط / Active' : 'غير نشط / Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <a class="view-link" href="{{ route('procedures.show', $procedure) }}">عرض / View</a>
                            <a class="edit-link" href="{{ route('procedures.edit', $procedure) }}">تعديل / Edit</a>

                            <form method="POST" action="{{ route('procedures.destroy', $procedure) }}" onsubmit="return confirm('هل أنت متأكد من حذف الإجراء؟ / Are you sure you want to delete this procedure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">حذف / Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">لا يوجد إجراءات حتى الآن / No procedures found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top:16px;">
            {{ $procedures->links() }}
        </div>
    </div>
</div>
</body>
</html>
