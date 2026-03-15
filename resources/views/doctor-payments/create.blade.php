<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة دفعة للطبيب</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#111827}
        .container{max-width:900px;margin:0 auto;padding:24px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:24px}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .full{grid-column:1 / -1}
        label{display:block;margin-bottom:8px;font-weight:700;font-size:14px}
        input,select,textarea{width:100%;box-sizing:border-box;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px}
        textarea{min-height:90px;resize:vertical}
        .btn{display:inline-block;text-decoration:none;border:none;background:#2563eb;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700;cursor:pointer}
        .btn-secondary{background:#0f172a}
        @media (max-width:768px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1 style="margin:0;">إضافة دفعة للطبيب / Add Doctor Payment</h1>
            <p style="margin:8px 0 0;color:#64748b;">{{ $doctor->display_name }}</p>
        </div>

        <a href="{{ route('doctor-statements.show', $doctor) }}" class="btn btn-secondary">رجوع / Back</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('doctor-payments.store', $doctor) }}">
            @csrf

            <div class="grid">
                <div>
                    <label for="amount">مبلغ الدفعة / Payment Amount</label>
                    <input id="amount" type="number" step="0.01" min="0.01" name="amount" required>
                </div>

                <div>
                    <label for="payment_method">طريقة الدفع / Payment Method</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="">اختر</option>
                        @foreach($paymentMethods as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="payment_date">تاريخ الدفع / Payment Date</label>
                    <input id="payment_date" type="datetime-local" name="payment_date" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>

                <div class="full">
                    <label for="notes">ملاحظات / Notes</label>
                    <textarea id="notes" name="notes"></textarea>
                </div>
            </div>

            <div style="margin-top:20px;">
                <button type="submit" class="btn">حفظ الدفعة / Save Payment</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>