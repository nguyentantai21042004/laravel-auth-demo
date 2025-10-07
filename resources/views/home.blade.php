<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Demo Project</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="content text-center">
            <div class="space-y-2">
                <h1>PHP Demo Project</h1>
                <p class="text-lg">Authentication & Profile Management</p>
            </div>

            <div class="pt-8 space-y-3">
                @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Đăng nhập
                </a>
                @endif

                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-secondary">
                    Đăng ký tài khoản
                </a>
                @endif
            </div>

            <div class="pt-8 text-sm">
                <p>Demo for PHP/Laravel Interview</p>
            </div>
        </div>
    </div>
</body>
</html>
