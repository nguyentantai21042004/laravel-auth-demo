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
            <div id="alert" class="alert" style="display:none"></div>
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
        // show notice if redirected after password change
        const params = new URLSearchParams(window.location.search);
        if (params.get('password_changed') === '1') {
            const alertBox = document.getElementById('alert');
            alertBox.textContent = 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại.';
            alertBox.style.display = 'block';
            alertBox.style.padding = '12px 16px';
            alertBox.style.borderRadius = '8px';
            alertBox.style.marginBottom = '16px';
            alertBox.style.border = '1px solid';
            alertBox.style.background = '#102a1b';
            alertBox.style.borderColor = '#065f46';
            alertBox.style.color = '#bbf7d0';
        }
        const form = document.querySelector('form[action="/login"][method="POST"]');
        if (!form) return;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const alertBox = document.getElementById('alert');

        function showAlert(message, type) {
            alertBox.textContent = message;
            alertBox.style.display = 'block';
            alertBox.style.padding = '12px 16px';
            alertBox.style.borderRadius = '8px';
            alertBox.style.marginBottom = '16px';
            alertBox.style.border = '1px solid';
            if (type === 'error') {
                alertBox.style.background = '#2f1d1d';
                alertBox.style.borderColor = '#7f1d1d';
                alertBox.style.color = '#fecaca';
            } else {
                alertBox.style.background = '#102a1b';
                alertBox.style.borderColor = '#065f46';
                alertBox.style.color = '#bbf7d0';
            }
        }

        function translate(msg) {
            const map = {
                'Invalid credentials':'Sai email hoặc mật khẩu',
                'The email has already been taken.':'Email đã được sử dụng',
                'The email field must be a valid email address.':'Email không hợp lệ',
                'The email field is required.':'Vui lòng nhập email',
                'The password field is required.':'Vui lòng nhập mật khẩu',
                'The password field must be at least 8 characters.':'Mật khẩu tối thiểu 8 ký tự',
                'The name field is required.':'Vui lòng nhập họ tên'
            };
            return map[msg] || msg;
        }

        function buildErrorMessage(data, fallback) {
            if (data && typeof data === 'object') {
                if (Array.isArray(data.errors)) {
                    return translate(String(data.errors[0]));
                }
                if (data.errors && typeof data.errors === 'object') {
                    const firstField = Object.keys(data.errors)[0];
                    if (firstField && Array.isArray(data.errors[firstField])) {
                        return translate(String(data.errors[firstField][0]));
                    }
                }
                if (data.message) return translate(String(data.message));
            }
            return translate(fallback || 'Có lỗi xảy ra');
        }
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
                if (!res.ok) {
                    const msg = buildErrorMessage(data, 'Đăng nhập thất bại');
                    showAlert(msg, 'error');
                    return;
                }
                if (data.token) {
                    localStorage.setItem('jwt', data.token);
                }
                window.location.href = '/dashboard';
            } catch (err) {
                showAlert(translate(err.message || 'Có lỗi xảy ra'), 'error');
            }
        });
    })();
    </script>
</body>
</html>
