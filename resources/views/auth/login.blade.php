<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CoreX Medical ERP') }} - Login | تسجيل الدخول</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 32px;
        }

        .brand {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand h1 {
            margin: 0 0 8px;
            font-size: 28px;
            color: #0f172a;
        }

        .brand p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.7;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            box-sizing: border-box;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: 0.2s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .form-check label {
            font-size: 14px;
            color: #374151;
        }

        .btn {
            width: 100%;
            border: none;
            border-radius: 10px;
            padding: 13px 16px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            background: #2563eb;
            color: white;
            transition: 0.2s ease;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .helper-box {
            margin-top: 22px;
            padding: 14px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            font-size: 13px;
            line-height: 1.8;
            color: #475569;
        }

        .helper-box strong {
            color: #0f172a;
        }

        .text-danger {
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
            display: block;
        }

        .footer-note {
            text-align: center;
            margin-top: 18px;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="login-card">
            <div class="brand">
                <h1>CoreX Medical ERP</h1>
                <p>تسجيل الدخول إلى كور إكس الطبي<br>Login to CoreX Medical ERP</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>حدث خطأ / An error occurred:</strong>
                    <ul style="margin: 8px 0 0; padding-inline-start: 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">البريد الإلكتروني / Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">كلمة المرور / Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control"
                        required
                    >
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember" value="1">
                    <label for="remember">تذكرني / Remember me</label>
                </div>

                <button type="submit" class="btn">
                    دخول / Login
                </button>
            </form>

            <div class="helper-box">
                <strong>بيانات الدخول الحالية / Current login credentials</strong><br>
                Email: admin@corex.local<br>
                Password: Admin@12345
            </div>

            <div class="footer-note">
                CoreX Medical ERP / كور إكس الطبي
            </div>
        </div>
    </div>
</body>
</html>