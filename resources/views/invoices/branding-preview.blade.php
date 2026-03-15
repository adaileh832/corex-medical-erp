<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Preview | معاينة الفاتورة</title>
    <style>
        @page { size: A4; margin: 14mm; }

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
            background: #fff;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin: 24px 0 12px;
            color: #111827;
        }

        .meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .box {
            background: #f8fafc;
            padding: 14px;
            border-radius: 12px;
        }

        .box .label {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .box .value {
            color: #111827;
            font-weight: 700;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 10px;
            text-align: right;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            color: #475569;
        }

        .totals {
            margin-top: 24px;
            max-width: 380px;
            margin-inline-start: auto;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .note {
            margin-top: 26px;
            padding: 14px;
            border-radius: 12px;
            background: #fff7ed;
            color: #9a3412;
            font-size: 14px;
            line-height: 1.8;
        }
    </style>
</head>
<body>
<div class="page">
    @include('invoices.partials.header')

    <div class="section-title">بيانات المريض / Patient Information</div>

    <div class="meta">
        <div class="box">
            <div class="label">اسم المريض</div>
            <div class="value">اسم تجريبي</div>
        </div>

        <div class="box">
            <div class="label">رقم الهاتف</div>
            <div class="value">07XXXXXXXX</div>
        </div>

        <div class="box">
            <div class="label">الجنس</div>
            <div class="value">أنثى / Female</div>
        </div>

        <div class="box">
            <div class="label">طريقة الدفع</div>
            <div class="value">Cash / نقدًا</div>
        </div>

        <div class="box">
            <div class="label">تاريخ الدفع</div>
            <div class="value">{{ now()->format('Y-m-d H:i') }}</div>
        </div>

        <div class="box">
            <div class="label">رقم الفاتورة</div>
            <div class="value">INV-PREVIEW-0001</div>
        </div>
    </div>

    <div class="section-title">تفاصيل الخدمات / Service Details</div>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>الإجراء / Procedure</th>
            <th>السعر / Price</th>
            <th>الكمية / Qty</th>
            <th>الإجمالي / Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>إجراء تجريبي</td>
            <td>100.00 د.أ</td>
            <td>1</td>
            <td>100.00 د.أ</td>
        </tr>
        <tr>
            <td>2</td>
            <td>إجراء آخر</td>
            <td>50.00 د.أ</td>
            <td>2</td>
            <td>100.00 د.أ</td>
        </tr>
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <strong>المجموع الفرعي</strong>
            <span>200.00 د.أ</span>
        </div>

        <div class="total-row">
            <strong>الخصم</strong>
            <span>10.00 د.أ</span>
        </div>

        <div class="total-row">
            <strong>الإجمالي المستحق</strong>
            <span>190.00 د.أ</span>
        </div>

        <div class="total-row">
            <strong>المدفوع</strong>
            <span>190.00 د.أ</span>
        </div>

        <div class="total-row" style="border-bottom:none;">
            <strong>المتبقي</strong>
            <span>0.00 د.أ</span>
        </div>
    </div>

    <div class="note">
        الختم غير مضاف داخل التصميم، وسيتم وضعه بعد الطباعة يدويًا حسب طلبكم.
    </div>
</div>
</body>
</html>