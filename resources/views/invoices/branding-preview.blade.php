<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Branding Preview | معاينة هوية الفاتورة</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f4f6;
            color: #111827;
            padding: 24px;
        }

        .page {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .section {
            margin-top: 24px;
        }

        .label {
            font-weight: 700;
            color: #374151;
        }

        .value {
            color: #111827;
            margin-top: 8px;
        }

        .badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            margin-inline-start: 8px;
            font-size: 13px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="page">
        @include('invoices.partials.header')

        <div class="section">
            <div class="label">طرق الدفع / Payment Methods</div>
            <div class="value">
                @foreach (config('hospital.payment_methods', []) as $method)
                    <span class="badge">{{ $method }}</span>
                @endforeach
            </div>
        </div>

        <div class="section">
            <div class="label">إعدادات الفاتورة / Invoice Rules</div>
            <div class="value">
                الضريبة: {{ config('hospital.has_tax') ? 'نعم' : 'لا' }}<br>
                الخصم: {{ config('hospital.has_discount') ? 'نعم' : 'لا' }}<br>
                الطباعة: {{ config('hospital.print_a4') ? 'A4' : 'غير محددة' }}<br>
                نوع الفاتورة: {{ config('hospital.patient_and_procedures_only') ? 'المريض + الإجراءات فقط' : 'غير محدد' }}
            </div>
        </div>
    </div>
</body>
</html>
