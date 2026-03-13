<!DOCTYPE html>
<html lang="{{ $appLocale }}" dir="{{ $appDirection }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $globalSettings['hospital_name'] ?? 'CoreX Medical ERP' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap{{ $appDirection === 'rtl' ? '.rtl' : '' }}.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fb;
            font-family: Tahoma, Arial, sans-serif;
        }

        .navbar-brand img {
            height: 42px;
            width: auto;
            object-fit: contain;
        }

        .content-card,
        .table-card,
        .stat-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        }

        .summary-box {
            border-radius: 14px;
            padding: 16px;
            background: #f8fafc;
            border: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    @php
        $hospitalName = $appLocale === 'ar'
            ? ($globalSettings['hospital_name'] ?? 'CoreX Medical ERP')
            : ($globalSettings['hospital_name_en'] ?? 'CoreX Medical ERP');

        $logoUrl = !empty($globalSettings['logo']) ? asset('storage/' . $globalSettings['logo']) : null;
    @endphp

    @auth
        <nav class="navbar navbar-expand-lg bg-white shadow-sm mb-4">
            <div class="container-fluid px-4">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo">
                    @endif
                    <span>{{ $hospitalName }}</span>
                </a>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-dark">{{ __('app.dashboard') }}</a>

                    @if(auth()->user()->hasPermission('manage-patients'))
                        <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-primary">{{ __('app.patients') }}</a>
                    @endif

                    @if(auth()->user()->hasPermission('manage-doctors'))
                        <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-outline-success">{{ __('app.doctors') }}</a>
                    @endif

                    @if(auth()->user()->hasPermission('manage-procedures'))
                        <a href="{{ route('procedures.index') }}" class="btn btn-sm btn-outline-info">{{ __('app.procedures') }}</a>
                    @endif

                    @if(auth()->user()->hasPermission('manage-invoices'))
                        <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-outline-warning">{{ __('app.invoices') }}</a>
                    @endif

                    @if(auth()->user()->hasPermission('manage-operations'))
                        <a href="{{ route('operations.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('app.operations') }}</a>
                    @endif

                    @if(
                        auth()->user()->hasPermission('manage-suppliers') ||
                        auth()->user()->hasPermission('manage-supplier-invoices') ||
                        auth()->user()->hasPermission('manage-supplier-payments') ||
                        auth()->user()->hasPermission('view-supplier-statements')
                    )
                        <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-primary">{{ __('app.suppliers') }}</a>
                    @endif

                    @if(
                        auth()->user()->hasPermission('manage-inventory-items') ||
                        auth()->user()->hasPermission('manage-stock-movements') ||
                        auth()->user()->hasPermission('view-inventory-reports')
                    )
                        <a href="{{ route('inventory-items.index') }}" class="btn btn-sm btn-outline-success">{{ __('app.inventory') }}</a>
                    @endif

                    @if(auth()->user()->hasPermission('view-doctor-statements'))
                        <a href="{{ route('doctor-statements.index') }}" class="btn btn-sm btn-outline-danger">{{ __('app.doctor_statements') }}</a>
                    @endif

                    @if(auth()->user()->hasPermission('view-supplier-statements'))
                        <a href="{{ route('supplier-statements.index') }}" class="btn btn-sm btn-outline-dark">{{ __('app.supplier_statements') }}</a>
                    @endif

                    <a href="{{ route('locale.switch', 'ar') }}" class="btn btn-sm btn-outline-secondary">AR</a>
                    <a href="{{ route('locale.switch', 'en') }}" class="btn btn-sm btn-outline-secondary">EN</a>

                    @if(auth()->user()->hasRole('manager'))
                        <a href="{{ route('settings.index') }}" class="btn btn-sm btn-primary">{{ __('app.settings') }}</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-danger">{{ __('app.logout') }}</button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth

    <main class="container pb-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>