<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app()->getLocale() === 'ar' ? 'إنشاء مدير النظام' : 'Create System Manager' }}</title>
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

        .setup-card {
            width: 100%;
            max-width: 520px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    <div class="card setup-card p-4">
        <div class="text-center mb-4">
            <h3 class="mb-2">
                {{ app()->getLocale() === 'ar' ? 'إنشاء مدير النظام' : 'Create System Manager' }}
            </h3>
            <p class="text-muted mb-0">
                {{ app()->getLocale() === 'ar'
                    ? 'هذه الخطوة تظهر مرة واحدة فقط قبل إنشاء أول مدير للنظام'
                    : 'This step appears only once before creating the first system manager' }}
            </p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('setup.manager.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('app.email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('app.password') }}</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    {{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}
                </label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-primary">
                    {{ app()->getLocale() === 'ar' ? 'إنشاء المدير' : 'Create Manager' }}
                </button>

                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                    {{ __('app.back') }}
                </a>
            </div>
        </form>
    </div>
</body>
</html>