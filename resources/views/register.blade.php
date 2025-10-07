<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - PHP Demo</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="content">
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
</body>
</html>
