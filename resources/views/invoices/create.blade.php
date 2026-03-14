<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة فاتورة | Add Invoice</title>
    <style>
        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f4f7fb;color:#111827}
        .container{max-width:1100px;margin:0 auto;padding:24px}
        .card{background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(15,23,42,.06);padding:24px}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .full{grid-column:1 / -1}
        label{display:block;margin-bottom:8px;font-weight:700;font-size:14px}
        input,select,textarea{width:100%;box-sizing:border-box;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px}
        textarea{min-height:100px;resize:vertical}
        .btn{display:inline-block;text-decoration:none;border:none;background:#2563eb;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700;cursor:pointer}
        .btn-secondary{background:#0f172a}
        .item-row{display:grid;grid-template-columns:2fr 1fr;gap:12px;margin-bottom:12px}
        .errors{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;padding:12px 14px;border-radius:10px;margin-bottom:16px}
        @media (max-width:768px){.grid,.item-row{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1 style="margin:0;">إضافة فاتورة / Add Invoice</h1>
            <p style="margin:8px 0 0;color:#64748b;">فاتورة المريض + الإجراءات فقط</p>
        </div>

        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">رجوع / Back</a>
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

        <form method="POST" action="{{ route('invoices.store') }}">
            @csrf

            <div class="grid">
                <div>
                    <label for="patient_id">المريض / Patient</label>
                    <select id="patient_id" name="patient_id" required>
                        <option value="">اختر المريض</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>
                                {{ $patient->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="payment_method">طريقة الدفع / Payment Method</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="">اختر</option>
                        @foreach($paymentMethods as $key => $label)
                            <option value="{{ $key }}" @selected(old('payment_method') == $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="discount">الخصم / Discount ({{ config('hospital.currency_symbol_ar') }})</label>
                    <input id="discount" type="number" step="0.01" min="0" name="discount" value="{{ old('discount', 0) }}">
                </div>

                <div>
                    <label for="paid_amount">المبلغ المدفوع / Paid Amount ({{ config('hospital.currency_symbol_ar') }})</label>
                    <input id="paid_amount" type="number" step="0.01" min="0" name="paid_amount" value="{{ old('paid_amount', 0) }}">
                </div>

                <div class="full">
                    <label>الإجراءات / Procedures</label>

                    <div class="item-row">
                        <select name="items[0][procedure_id]" required>
                            <option value="">اختر الإجراء</option>
                            @foreach($procedures as $procedure)
                                <option value="{{ $procedure->id }}">
                                    {{ $procedure->display_name }} - {{ number_format((float)$procedure->price, 2) }} {{ config('hospital.currency_symbol_ar') }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" min="1" name="items[0][quantity]" value="1" placeholder="الكمية / Quantity" required>
                    </div>

                    <div class="item-row">
                        <select name="items[1][procedure_id]">
                            <option value="">اختر الإجراء</option>
                            @foreach($procedures as $procedure)
                                <option value="{{ $procedure->id }}">
                                    {{ $procedure->display_name }} - {{ number_format((float)$procedure->price, 2) }} {{ config('hospital.currency_symbol_ar') }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" min="1" name="items[1][quantity]" value="1" placeholder="الكمية / Quantity">
                    </div>

                    <div class="item-row">
                        <select name="items[2][procedure_id]">
                            <option value="">اختر الإجراء</option>
                            @foreach($procedures as $procedure)
                                <option value="{{ $procedure->id }}">
                                    {{ $procedure->display_name }} - {{ number_format((float)$procedure->price, 2) }} {{ config('hospital.currency_symbol_ar') }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" min="1" name="items[2][quantity]" value="1" placeholder="الكمية / Quantity">
                    </div>
                </div>

                <div class="full">
                    <label for="notes">ملاحظات / Notes</label>
                    <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div style="margin-top:20px;">
                <button type="submit" class="btn">حفظ الفاتورة / Save Invoice</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
