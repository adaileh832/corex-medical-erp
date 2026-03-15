<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:24px;border-bottom:2px solid #e5e7eb;padding-bottom:18px;margin-bottom:24px;">
    <div style="display:flex;align-items:flex-start;gap:16px;">
        @php
            $logoPath = public_path(config('hospital.logo_path'));
        @endphp

        @if (file_exists($logoPath))
            <img
                src="{{ asset(config('hospital.logo_path')) }}"
                alt="Hospital Logo"
                style="width:90px;height:90px;object-fit:contain;"
            >
        @endif

        <div>
            <h1 style="margin:0;font-size:28px;color:#111827;">
                {{ config('hospital.name_ar') }}
            </h1>

            <div style="margin-top:10px;color:#4b5563;font-size:14px;line-height:1.9;">
                <div>الهاتف / Phone: {{ config('hospital.phone') }}</div>
                <div>الجوال / Mobile: {{ config('hospital.mobile') }}</div>
                <div>البريد / Email: {{ config('hospital.email') }}</div>
                <div>العنوان / Address: {{ config('hospital.address') }}</div>
            </div>
        </div>
    </div>

    <div style="text-align:end;">
        <div style="font-size:14px;color:#6b7280;">فاتورة طبية / Medical Invoice</div>
        <div style="margin-top:10px;font-size:17px;font-weight:700;color:#111827;">
            {{ config('hospital.currency_name_ar') }} - {{ config('hospital.currency_symbol_ar') }}
        </div>
    </div>
</div>