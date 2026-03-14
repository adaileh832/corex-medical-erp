<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoreX Dashboard | لوحة التحكم</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        .layout {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #0f172a;
            color: #fff;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .topbar h1 {
            margin: 0;
            font-size: 24px;
        }

        .topbar p {
            margin: 6px 0 0;
            color: #cbd5e1;
            font-size: 14px;
        }

        .topbar .user-box {
            text-align: end;
        }

        .container {
            width: 100%;
            max-width: 1300px;
            margin: 0 auto;
            padding: 24px;
        }

        .grid {
            display: grid;
            gap: 18px;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            margin-bottom: 24px;
        }

        .two-col {
            grid-template-columns: 2fr 1fr;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            padding: 20px;
        }

        .card h2 {
            margin: 0 0 8px;
            font-size: 18px;
            color: #0f172a;
        }

        .card p {
            margin: 0;
            color: #64748b;
            line-height: 1.7;
        }

        .stat-title {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
        }

        .stat-sub {
            margin-top: 8px;
            font-size: 13px;
            color: #94a3b8;
        }

        .actions {
            display: grid;
            gap: 12px;
            margin-top: 16px;
        }

        .action-link {
            display: block;
            text-decoration: none;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            padding: 12px 14px;
            border-radius: 10px;
            font-weight: 700;
        }

        .action-link:hover {
            background: #dbeafe;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        th, td {
            padding: 12px 10px;
            text-align: start;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        th {
            color: #475569;
            background: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            background: #eef2ff;
            color: #4338ca;
        }

        .logout-form {
            margin: 0;
        }

        .logout-btn {
            border: none;
            background: #dc2626;
            color: white;
            padding: 10px 14px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }

        @media (max-width: 900px) {
            .two-col {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="layout">
    <div class="topbar">
        <div>
            <h1>CoreX Medical ERP</h1>
            <p>لوحة التحكم الرئيسية / Main Dashboard</p>
        </div>

        <div class="user-box">
            <div style="margin-bottom: 10px;">
                {{ $currentUser?->name ?? 'User' }}<br>
                <span style="color:#cbd5e1; font-size:13px;">
                    {{ $currentUser?->email ?? '---' }}
                </span>
            </div>

            <form method="POST" action="{{ url('/logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">تسجيل الخروج / Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="grid stats-grid">
            <div class="card">
                <div class="stat-title">المرضى / Patients</div>
                <div class="stat-value">{{ $stats['patients'] }}</div>
                <div class="stat-sub">إجمالي المرضى المسجلين</div>
            </div>

            <div class="card">
                <div class="stat-title">الأطباء / Doctors</div>
                <div class="stat-value">{{ $stats['doctors'] }}</div>
                <div class="stat-sub">إجمالي الأطباء في النظام</div>
            </div>

            <div class="card">
                <div class="stat-title">الفواتير / Invoices</div>
                <div class="stat-value">{{ $stats['invoices'] }}</div>
                <div class="stat-sub">عدد الفواتير الحالية</div>
            </div>

            <div class="card">
                <div class="stat-title">المستخدمون / Users</div>
                <div class="stat-value">{{ $stats['users'] }}</div>
                <div class="stat-sub">حسابات المستخدمين</div>
            </div>

            <div class="card">
                <div class="stat-title">المدفوعات / Payments</div>
                <div class="stat-value">{{ $stats['payments'] }}</div>
                <div class="stat-sub">عدد المدفوعات المسجلة</div>
            </div>

            <div class="card">
                <div class="stat-title">الموظفون / Employees</div>
                <div class="stat-value">{{ $stats['employees'] }}</div>
                <div class="stat-sub">إجمالي الموظفين</div>
            </div>
        </div>

        <div class="grid two-col">
            <div class="card">
                <h2>آخر الفواتير / Recent Invoices</h2>
                <p>عرض مختصر لآخر الفواتير داخل النظام.</p>

                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>رقم الفاتورة / Invoice</th>
                        <th>الإجمالي / Total</th>
                        <th>المدفوع / Paid</th>
                        <th>الحالة / Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($recentInvoices as $index => $invoice)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $invoice->invoice_number ?? ('INV-' . $invoice->id) }}</td>
                            <td>{{ number_format((float) ($invoice->total ?? 0), 2) }}</td>
                            <td>{{ number_format((float) ($invoice->paid_amount ?? 0), 2) }}</td>
                            <td>
                                <span class="badge">
                                    {{ $invoice->status ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">لا توجد فواتير حتى الآن / No invoices found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h2>إجراءات سريعة / Quick Actions</h2>
                <p>روابط سريعة للخطوات القادمة داخل CoreX.</p>

                <div class="actions">
                    <a href="{{ url('/dashboard') }}" class="action-link">العودة للوحة التحكم / Refresh Dashboard</a>
                    <a href="{{ url('/login') }}" class="action-link">صفحة الدخول / Login Page</a>
                </div>

                <div style="margin-top: 22px;">
                    <h2>ملخص مالي / Financial Summary</h2>
                    <p style="margin-top: 10px;">إجمالي الفواتير: <strong>{{ number_format($financial['invoice_total'], 2) }}</strong></p>
                    <p style="margin-top: 10px;">إجمالي المدفوع من الفواتير: <strong>{{ number_format($financial['paid_total'], 2) }}</strong></p>
                    <p style="margin-top: 10px;">إجمالي المدفوعات: <strong>{{ number_format($financial['payments_total'], 2) }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>