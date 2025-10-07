<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PHP Demo</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="content-wide">
            <nav class="nav">
                <div>
                    <h2 style="margin: 0;">Dashboard</h2>
                </div>
                <div class="nav-links">
                    <a href="/dashboard" class="nav-link">Dashboard</a>
                    <a href="/profile" class="nav-link">Hồ sơ</a>
                    <form action="/logout" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">Đăng xuất</button>
                    </form> 
                </div>
            </nav>

            <div class="space-y-2 mb-6">
                <h1>Chào mừng, Nguyễn Văn A!</h1>
                <p>Đây là trang dashboard được bảo vệ bởi middleware auth</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Session Active</div>
                    <div class="stat-value">✓</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Middleware</div>
                    <div class="stat-value">Auth</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Password Hash</div>
                    <div class="stat-value">✓</div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin tài khoản</h3>
                        <p class="card-description">Dữ liệu được lấy từ session Laravel</p>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="link-muted">Email:</span>
                            <span>user@example.com</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="link-muted">Tên:</span>
                            <span>Nguyễn Văn A</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="link-muted">Ngày tạo:</span>
                            <span>01/01/2025</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Các khái niệm Laravel</h3>
                        <p class="card-description">Điểm nói trong phỏng vấn</p>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <strong>Session Management:</strong>
                            <p class="text-sm">Laravel lưu user_id vào session khi đăng nhập thành công</p>
                        </div>
                        <div>
                            <strong>Middleware Auth:</strong>
                            <p class="text-sm">Kiểm tra session trước khi cho phép truy cập route được bảo vệ</p>
                        </div>
                        <div>
                            <strong>Password Hashing:</strong>
                            <p class="text-sm">Sử dụng Hash::make() để mã hóa mật khẩu trước khi lưu database</p>
                        </div>
                        <div>
                            <strong>Form Validation:</strong>
                            <p class="text-sm">$request->validate([...]) kiểm tra dữ liệu đầu vào</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách người dùng (API)</h3>
                        <p class="card-description">Render từ dữ liệu /api/users</p>
                    </div>
                    <div id="users-list" class="space-y-2">
                        <div class="text-sm link-muted">Đang tải...</div>
                    </div>
                </div>
            </div>

            <div class="pt-8 space-y-3">
                <a href="/profile" class="btn btn-primary">
                    Chỉnh sửa hồ sơ
                </a>
            </div>
        </div>
    </div>
    <script>
    (function() {
        const usersEl = document.getElementById('users-list');
        if (!usersEl) return;
        const token = localStorage.getItem('jwt');
        fetch('/api/users', {
            headers: token ? { 'Authorization': 'Bearer ' + token } : {}
        })
            .then(async (res) => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then((data) => {
                // Laravel paginator returns { data: [...], ... }
                const list = Array.isArray(data?.data) ? data.data : (Array.isArray(data) ? data : []);
                if (!list.length) {
                    usersEl.innerHTML = '<div class="text-sm link-muted">Không có người dùng</div>';
                    return;
                }
                usersEl.innerHTML = '';
                list.forEach((u) => {
                    const row = document.createElement('div');
                    row.className = 'flex justify-between card';
                    row.style.padding = '8px 12px';
                    row.innerHTML = '<span>'+ (u.name || '(no name)') +'</span><span class="link-muted">'+ (u.email || '') +'</span>';
                    usersEl.appendChild(row);
                });
            })
            .catch((err) => {
                usersEl.innerHTML = '<div class="text-sm" style="color:#ef4444">Lỗi tải người dùng: '+ err.message +'</div>';
            });
    })();
    </script>
</body>
</html>
