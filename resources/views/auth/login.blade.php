<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('app.login') }} - CoreX Medical ERP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap{{ app()->getLocale() === 'ar' ? '.rtl' : '' }}.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #edf4ff 0%, #f7faff 45%, #eef2ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Tahoma, Arial, sans-serif;
            color: #172033;
        }

        .login-shell {
            width: 100%;
            max-width: 520px;
            padding: 20px;
        }

        .login-card {
            border: 1px solid rgba(37, 99, 235, 0.08);
            border-radius: 24px;
            box-shadow: 0 18px 60px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(10px);
        }

        .language-switch .btn {
            min-width: 48px;
            border-radius: 12px;
        }

        .brand-logo {
            max-height: 76px;
            width: auto;
            object-fit: contain;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.08);
            color: #1d4ed8;
            font-size: 13px;
            font-weight: 700;
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 14px;
            border-color: #d8e1f0;
        }

        .form-control:focus {
            border-color: #6ea8fe;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
        }

        .btn {
            border-radius: 14px;
            padding: 11px 16px;
            font-weight: 600;
        }

        .setup-manager-btn {
            text-decoration: none;
        }

        .support-text {
            font-size: 0.92rem;
        }
    </style>
</head>
<body>
    @php
        $settings = \App\Models\Setting::query()->pluck('value', 'key');
        $logoUrl = !empty($settings['logo']) ? asset('storage/' . $settings['logo']) : null;
        $hospitalName = app()->getLocale() === 'ar'
            ? ($settings['hospital_name'] ?? 'CoreX Medical ERP')
            : ($settings['hospital_name_en'] ?? 'CoreX Medical ERP');
    @endphp

    <div class="login-shell">
        <div class="card login-card p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
                <span class="brand-badge">CoreX Medical ERP</span>

                <div class="language-switch d-flex gap-2">
                    <a href="{{ route('locale.switch', 'ar') }}" class="btn btn-sm btn-outline-secondary">AR</a>
                    <a href="{{ route('locale.switch', 'en') }}" class="btn btn-sm btn-outline-secondary">EN</a>
                </div>
            </div>

            <div class="text-center mb-4">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" class="brand-logo mb-3">
                @endif
                <h1 class="h2 mb-2">{{ $hospitalName }}</h1>
                <p class="text-muted mb-0 support-text">{{ __('app.login_subtitle') }}</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-4">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="mt-4">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">{{ __('app.email') }}</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">{{ __('app.password') }}</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label for="remember" class="form-check-label">{{ __('app.remember_me') }}</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    {{ __('app.login') }}
                </button>
            </form>

            @if(!$managerExists)
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted support-text mb-3">{{ __('app.first_time_setup_hint') }}</p>
                    <a href="{{ route('setup.manager') }}" class="btn btn-dark w-100 setup-manager-btn">
                        {{ __('app.create_system_manager') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
