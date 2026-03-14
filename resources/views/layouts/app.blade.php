<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $globalSettings['hospital_name'] ?? 'CoreX Medical ERP' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --corex-bg: #f8fafc;
            --corex-surface: #ffffff;
            --corex-text: #0f172a;
            --corex-muted: #64748b;
            --corex-primary: #2563eb;
            --corex-border: #e2e8f0;
            --corex-sidebar: #0f172a;
            --corex-sidebar-text: rgba(255,255,255,0.88);
        }

        body {
            background: var(--corex-bg);
            color: var(--corex-text);
        }

        .corex-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px 1fr;
        }

        .corex-sidebar {
            background: var(--corex-sidebar);
            color: var(--corex-sidebar-text);
            padding: 24px 18px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .corex-brand {
            display: block;
            text-decoration: none;
            color: #fff;
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 24px;
        }

        .corex-sidebar-section {
            margin-bottom: 18px;
        }

        .corex-sidebar-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255,255,255,0.5);
            margin-bottom: 10px;
            display: block;
        }

        .corex-sidebar-link {
            display: block;
            padding: 12px 14px;
            color: var(--corex-sidebar-text);
            text-decoration: none;
            border-radius: 14px;
            margin-bottom: 8px;
            background: transparent;
        }

        .corex-sidebar-link:hover,
        .corex-sidebar-link.active {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        .corex-main {
            padding: 24px;
        }

        .corex-topbar {
            background: var(--corex-surface);
            border: 1px solid var(--corex-border);
            border-radius: 20px;
            padding: 18px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .corex-topbar-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin: 0;
        }

        .corex-topbar-subtitle {
            color: var(--corex-muted);
            margin: 0;
        }

        .corex-user-chip {
            background: #eff6ff;
            color: var(--corex-primary);
            padding: 10px 14px;
            border-radius: 999px;
            font-weight: 700;
            white-space: nowrap;
        }

        .corex-content {
            min-width: 0;
        }

        @media (max-width: 991.98px) {
            .corex-shell {
                grid-template-columns: 1fr;
            }

            .corex-sidebar {
                position: relative;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="corex-shell">
        <aside class="corex-sidebar">
            <a href="{{ route('dashboard') }}" class="corex-brand">
                {{ $globalSettings['hospital_name'] ?? 'CoreX Medical ERP' }}
            </a>

            <div class="corex-sidebar-section">
                <span class="corex-sidebar-label">Main / الرئيسية</span>
                <a href="{{ route('dashboard') }}" class="corex-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    {{ __('app.dashboard') }}
                </a>
            </div>

            <div class="corex-sidebar-section">
                <span class="corex-sidebar-label">Operations / العمليات</span>

                @can('manage-patients')
                    <a href="{{ route('patients.index') }}" class="corex-sidebar-link">{{ __('app.patients') }}</a>
                @endcan

                @can('manage-doctors')
                    <a href="{{ route('doctors.index') }}" class="corex-sidebar-link">{{ __('app.doctors') }}</a>
                @endcan

                @can('manage-operations')
                    <a href="{{ route('operations.index') }}" class="corex-sidebar-link">{{ __('app.operations') }}</a>
                @endcan

                @can('manage-invoices')
                    <a href="{{ route('invoices.index') }}" class="corex-sidebar-link">{{ __('app.invoices') }}</a>
                @endcan

                @can('manage-suppliers')
                    <a href="{{ route('suppliers.index') }}" class="corex-sidebar-link">{{ __('app.suppliers') }}</a>
                @endcan

                @can('manage-inventory-items')
                    <a href="{{ route('inventory-items.index') }}" class="corex-sidebar-link">{{ __('app.inventory') }}</a>
                @endcan
            </div>

            <div class="corex-sidebar-section">
                <span class="corex-sidebar-label">System / النظام</span>

                @can('manage-users')
                    <a href="{{ route('users.index') }}" class="corex-sidebar-link">{{ __('app.users') }}</a>
                @endcan

                @can('manage-roles')
                    <a href="{{ route('roles.index') }}" class="corex-sidebar-link">{{ __('app.roles') }}</a>
                @endcan

                @can('manage-permissions')
                    <a href="{{ route('permissions.index') }}" class="corex-sidebar-link">{{ __('app.permissions') }}</a>
                @endcan

                @can('manage-settings')
                    <a href="{{ route('settings.index') }}" class="corex-sidebar-link">{{ __('app.settings') }}</a>
                @endcan
            </div>
        </aside>

        <main class="corex-main">
            <div class="corex-topbar">
                <div>
                    <h1 class="corex-topbar-title">@yield('page_title', __('app.dashboard'))</h1>
                    <p class="corex-topbar-subtitle">
                        CoreX Medical ERP / {{ __('app.dashboard_welcome') }}
                    </p>
                </div>

                <div class="corex-user-chip">
                    {{ auth()->user()->name ?? 'User' }}
                </div>
            </div>

            <div class="corex-content">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
