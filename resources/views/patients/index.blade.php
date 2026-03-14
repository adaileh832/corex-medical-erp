<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients | المرضى</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#1f2937}
        .container{max-width:1200px;margin:0 auto;padding:24px}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:20px}
        .btn{display:inline-block;text-decoration:none;border:none;background:#2563eb;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700;cursor:pointer}
        .btn-secondary{background:#0f172a}
        .btn-warning{background:#d97706}
        .btn-danger{background:#dc2626}
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
        .empty{padding:20px;text-align:center;color:#64748b}
        form{margin:0}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1 style="margin:0;">المرضى / Patients</h1>
            <p style="margin:8px 0 0;color:#64748b;">إدارة المرضى داخل CoreX Medical ERP</p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ url('/dashboard') }}" class="btn btn-secondary">الداشبورد / Dashboard</a>
            <a href="{{ route('patients.create') }}" class="btn">إضافة مريض / Add Patient</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>الاسم / Name</th>
                <th>الهاتف / Phone</th>
                <th>البريد / Email</th>
                <th>الجنس / Gender</th>
                <th>العمليات / Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($patients as $patient)
                <tr>
                    <td>{{ $patient->id }}</td>
                    <td>{{ $patient->display_name }}</td>
                    <td>{{ $patient->phone ?? '-' }}</td>
                    <td>{{ $patient->email ?? '-' }}</td>
                    <td>{{ $patient->gender ?? '-' }}</td>
                    <td>
                        <div class="actions">
                            <a class="view-link" href="{{ route('patients.show', $patient) }}">عرض / View</a>
                            <a class="edit-link" href="{{ route('patients.edit', $patient) }}">تعديل / Edit</a>

                            <form method="POST" action="{{ route('patients.destroy', $patient) }}" onsubmit="return confirm('هل أنت متأكد من حذف المريض؟ / Are you sure you want to delete this patient?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">حذف / Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty">لا يوجد مرضى حتى الآن / No patients found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top:16px;">
            {{ $patients->links() }}
        </div>
    </div>
</div>
</body>
</html>