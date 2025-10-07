<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - PHP Demo</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="content">
            <div id="alert" class="alert" style="display:none"></div>
            <div class="space-y-2 mb-6">
                <h1>Đăng ký tài khoản</h1>
                <p>Tạo tài khoản mới để sử dụng hệ thống</p>
            </div>

            <form action="/register" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            placeholder="Nguyễn Văn A"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="your@email.com"
                            required
                        >
                        <div class="form-hint">Sử dụng email hợp lệ để xác thực tài khoản</div>
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
                        <div class="form-hint">Tối thiểu 8 ký tự</div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Xác nhận mật khẩu</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Đăng ký
                </button>
            </form>

            <div class="text-center text-sm pt-4">
                <span class="link-muted">Đã có tài khoản? </span>
                <a href="/login" class="link-underline">Đăng nhập</a>
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
        const form = document.querySelector('form[action="/register"][method="POST"]');
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
                const res = await fetch('/register', {
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
                    const msg = buildErrorMessage(data, 'Đăng ký thất bại');
                    showAlert(msg, 'error');
                    return;
                }
                window.location.href = '/login';
            } catch (err) {
                showAlert(translate(err.message || 'Có lỗi xảy ra'), 'error');
            }
        });
    })();
    </script>
</body>
</html>
