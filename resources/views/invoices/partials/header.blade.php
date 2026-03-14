<div style="display:flex;align-items:center;justify-content:space-between;gap:20px;border-bottom:2px solid #e5e7eb;padding-bottom:16px;margin-bottom:24px;">
    <div style="display:flex;align-items:center;gap:16px;">
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
            <h1 style="margin:0;font-size:26px;color:#111827;">
                {{ config('hospital.name_ar') }}
            </h1>

            <p style="margin:8px 0 0;color:#6b7280;font-size:14px;">
                فاتورة طبية / Medical Invoice
            </p>
        </div>
    </div>

    <div style="text-align:end;">
        <div style="font-size:14px;color:#6b7280;">
            العملة / Currency
        </div>
        <div style="font-size:18px;font-weight:700;color:#111827;">
            {{ config('hospital.currency_name_ar') }} - {{ config('hospital.currency_symbol_ar') }}
        </div>
    </div>
</div>
