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
            background: linear-gradient(135deg, #eef4ff 0%, #f8fbff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Tahoma, Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 460px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.08);
        }

        .brand-logo {
            max-height: 70px;
            width: auto;
            object-fit: contain;
        }

        .setup-manager-btn {
            text-decoration: none;
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

    <div class="card login-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('locale.switch', 'ar') }}" class="btn btn-sm btn-outline-secondary">AR</a>
                <a href="{{ route('locale.switch', 'en') }}" class="btn btn-sm btn-outline-secondary">EN</a>
            </div>
        </div>

        <div class="text-center mb-4">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo" class="brand-logo mb-3">
            @endif
            <h3 class="mb-1">{{ $hospitalName }}</h3>
            <p class="text-muted mb-0">{{ __('app.login_subtitle') }}</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">{{ __('app.email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('app.password') }}</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label for="remember" class="form-check-label">{{ __('app.remember_me') }}</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                {{ __('app.login') }}
            </button>
        </form>

        @if(!$managerExists)
            <div class="text-center mt-4">
                <a href="{{ route('setup.manager') }}" class="btn btn-dark w-100 setup-manager-btn">
                    {{ app()->getLocale() === 'ar' ? 'إنشاء مدير النظام' : 'Create System Manager' }}
                </a>
            </div>
        @endif
    </div>
</body>
</html>