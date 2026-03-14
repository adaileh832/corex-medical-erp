@extends('layouts.app')

@section('content')
    <style>
        .corex-dashboard-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .corex-hero-card,
        .corex-panel,
        .corex-stat-card,
        .corex-action-card,
        .corex-module-card {
            border: none;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .corex-hero-card {
            padding: 28px;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
            color: #ffffff;
            overflow: hidden;
            position: relative;
        }

        .corex-hero-card::after {
            content: '';
            position: absolute;
            inset-inline-end: -40px;
            top: -30px;
            width: 180px;
            height: 180px;
            background: rgba(255, 255, 255, 0.10);
            border-radius: 50%;
        }

        .corex-hero-subtitle {
            color: rgba(255, 255, 255, 0.82);
            max-width: 720px;
        }

        .corex-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .corex-grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 20px;
        }

        .corex-col-8 { grid-column: span 8; }
        .corex-col-4 { grid-column: span 4; }
        .corex-col-6 { grid-column: span 6; }
        .corex-col-12 { grid-column: span 12; }

        .corex-panel {
            padding: 24px;
        }

        .corex-panel-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: #0f172a;
        }

        .corex-panel-text {
            color: #64748b;
            margin-bottom: 0;
        }

        .corex-stat-card {
            padding: 22px;
            height: 100%;
        }

        .corex-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .corex-stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            background: #eff6ff;
        }

        .corex-stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            line-height: 1;
        }

        .corex-stat-label {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .corex-stat-description {
            color: #64748b;
            margin-bottom: 0;
        }

        .corex-overview-list,
        .corex-actions-list,
        .corex-modules-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .corex-overview-item,
        .corex-action-card,
        .corex-module-card {
            padding: 16px 18px;
            border: 1px solid #e2e8f0;
        }

        .corex-overview-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 18px;
            background: #f8fafc;
        }

        .corex-overview-item strong {
            color: #0f172a;
        }

        .corex-overview-value {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1d4ed8;
        }

        .corex-action-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            border-radius: 18px;
            text-decoration: none;
            transition: all 0.2s ease;
            color: inherit;
        }

        .corex-action-card:hover {
            transform: translateY(-2px);
            border-color: #93c5fd;
            box-shadow: 0 10px 24px rgba(29, 78, 216, 0.10);
        }

        .corex-action-label {
            font-weight: 700;
            color: #0f172a;
        }

        .corex-action-arrow {
            font-size: 1.2rem;
            color: #1d4ed8;
        }

        .corex-module-card {
            border-radius: 20px;
            height: 100%;
        }

        .corex-module-title {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .corex-module-description {
            color: #64748b;
            margin-bottom: 0;
        }

        .corex-notes-box {
            border-radius: 20px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            padding: 18px;
            color: #475569;
        }

        .tone-primary .corex-stat-icon { background: #dbeafe; }
        .tone-success .corex-stat-icon { background: #dcfce7; }
        .tone-warning .corex-stat-icon { background: #fef3c7; }
        .tone-danger .corex-stat-icon { background: #fee2e2; }
        .tone-info .corex-stat-icon { background: #cffafe; }
        .tone-secondary .corex-stat-icon { background: #e2e8f0; }

        @media (max-width: 991.98px) {
            .corex-col-8,
            .corex-col-4,
            .corex-col-6,
            .corex-col-12 {
                grid-column: span 12;
            }
        }
    </style>

    <div class="corex-dashboard-page">
        <section class="corex-hero-card">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 position-relative">
                <div>
                    <h1 class="mb-2">{{ __('app.dashboard') }} - CoreX Medical ERP</h1>
                    <p class="corex-hero-subtitle mb-0">
                        {{ __('app.dashboard_welcome') }}, {{ auth()->user()->name }}.
                        لوحة متابعة تشغيلية حديثة تساعدك على إدارة المرضى، الأطباء، الفواتير، العمليات، المخزون، والموارد البشرية من مكان واحد.
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <span class="corex-chip">{{ __('app.current_role') }}</span>
                    <span class="corex-chip">CoreX</span>
                </div>
            </div>
        </section>

        <section class="corex-grid">
            <div class="corex-col-8">
                <div class="corex-panel">
                    <div class="mb-4">
                        <h2 class="corex-panel-title">Overview / ملخص عام</h2>
                        <p class="corex-panel-text">مؤشرات سريعة عن أهم وحدات النظام الحالية.</p>
                    </div>

                    <div class="row g-4">
                        @foreach ($stats as $stat)
                            <div class="col-md-6 col-xl-4">
                                <div class="corex-stat-card tone-{{ $stat['tone'] }}">
                                    <div class="corex-stat-top">
                                        <span class="corex-stat-icon">{{ $stat['icon'] }}</span>
                                    </div>
                                    <div class="corex-stat-value">{{ number_format($stat['value']) }}</div>
                                    <div class="corex-stat-label">{{ $stat['label'] }}</div>
                                    <p class="corex-stat-description">{{ $stat['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="corex-col-4">
                <div class="corex-panel h-100">
                    <div class="mb-4">
                        <h2 class="corex-panel-title">System Snapshot / نظرة سريعة</h2>
                        <p class="corex-panel-text">أرقام إدارية وتشغيلية أساسية للنظام.</p>
                    </div>

                    <div class="corex-overview-list">
                        @foreach ($systemOverview as $item)
                            <div class="corex-overview-item">
                                <strong>{{ $item['label'] }}</strong>
                                <span class="corex-overview-value">{{ number_format($item['value']) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="corex-notes-box mt-4">
                        <strong>Next Step / الخطوة التالية:</strong>
                        تحسين الـ layout العام وتحويله إلى SaaS layout أوضح وأسهل.
                    </div>
                </div>
            </div>
        </section>

        <section class="corex-grid">
            <div class="corex-col-6">
                <div class="corex-panel h-100">
                    <div class="mb-4">
                        <h2 class="corex-panel-title">Quick Actions / إجراءات سريعة</h2>
                        <p class="corex-panel-text">اختصارات مباشرة لأكثر العمليات استخدامًا.</p>
                    </div>

                    <div class="corex-actions-list">
                        @foreach ($quickActions as $action)
                            @if(auth()->user()->hasPermission($action['permission']))
                                <a href="{{ $action['route'] }}" class="corex-action-card">
                                    <span class="corex-action-label">{{ $action['label'] }}</span>
                                    <span class="corex-action-arrow">→</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="corex-col-6">
                <div class="corex-panel h-100">
                    <div class="mb-4">
                        <h2 class="corex-panel-title">Core Modules / الوحدات الأساسية</h2>
                        <p class="corex-panel-text">تنظيم أولي للوظائف الرئيسية داخل النظام.</p>
                    </div>

                    <div class="row g-3">
                        @foreach ($modules as $module)
                            <div class="col-md-6">
                                <div class="corex-module-card">
                                    <div class="corex-module-title">{{ $module['title'] }}</div>
                                    <p class="corex-module-description">{{ $module['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
