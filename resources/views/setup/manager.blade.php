<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('app.create_system_manager') }} - CoreX Medical ERP</title>
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

        .setup-shell {
            width: 100%;
            max-width: 580px;
            padding: 20px;
        }

        .setup-card {
            border: 1px solid rgba(37, 99, 235, 0.08);
            border-radius: 24px;
            box-shadow: 0 18px 60px rgba(15, 23, 42, 0.12);
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
    </style>
</head>
<body>
    <div class="setup-shell">
        <div class="card setup-card p-4 p-md-5">
            <div class="text-center mb-4">
                <h1 class="h3 mb-2">{{ __('app.create_system_manager') }}</h1>
                <p class="text-muted mb-0">{{ __('app.create_system_manager_subtitle') }}</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger rounded-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('setup.manager.store') }}" class="mt-4">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">{{ __('app.name') }}</label>
                    <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">{{ __('app.email') }}</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">{{ __('app.password') }}</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">{{ __('app.confirm_password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary">
                        {{ __('app.create_manager') }}
                    </button>

                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        {{ __('app.back_to_login') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
