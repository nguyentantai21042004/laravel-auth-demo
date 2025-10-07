<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - PHP Demo</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="space-y-2 mb-6">
                <h1>Đăng nhập</h1>
                <p>Nhập thông tin để truy cập dashboard</p>
            </div>

            <form action="/login" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="your@email.com"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Đăng nhập
                </button>
            </form>

            <div class="text-center text-sm pt-4">
                <span class="link-muted">Chưa có tài khoản? </span>
                <a href="/register" class="link-underline">Đăng ký ngay</a>
            </div>

            <div class="pt-4">
                <a href="/" class="text-sm link-muted">
                    ← Về trang chủ
                </a>
            </div>
        </div>
    </div>
    <script>
    (function() {
        const form = document.querySelector('form[action="/login"][method="POST"]');
        if (!form) return;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());
            try {
                const res = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json().catch(() => ({ ok: res.ok }));
                if (!res.ok) throw new Error(data.message || 'Đăng nhập thất bại');
                if (data.token) {
                    localStorage.setItem('jwt', data.token);
                }
                window.location.href = '/dashboard';
            } catch (err) {
                console.error(err);
            }
        });
    })();
    </script>
</body>
</html>
