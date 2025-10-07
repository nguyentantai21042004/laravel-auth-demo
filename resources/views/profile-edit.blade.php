<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa hồ sơ - PHP Demo</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="content-wide">
            <nav class="nav">
                <div>
                    <h2 style="margin: 0;">Chỉnh sửa hồ sơ</h2>
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
                <h1>Chỉnh sửa thông tin cá nhân</h1>
                <p>Cập nhật thông tin tài khoản của bạn</p>
            </div>

            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Thông tin cá nhân</h3>
                    <p class="card-description">Cập nhật tên và email của bạn</p>
                </div>
                
                <form action="/profile/update" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="Nguyễn Văn A"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="user@example.com"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="bio">Giới thiệu</label>
                        <textarea 
                            id="bio" 
                            name="bio" 
                            placeholder="Viết vài dòng về bản thân..."
                        ></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Lưu thay đổi
                    </button>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Đổi mật khẩu</h3>
                    <p class="card-description">Cập nhật mật khẩu để bảo mật tài khoản</p>
                </div>
                
                <form action="/profile/password" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="current_password">Mật khẩu hiện tại</label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới</label>
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            placeholder="••••••••"
                            required
                        >
                        <div class="form-hint">Tối thiểu 8 ký tự</div>
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                        <input 
                            type="password" 
                            id="new_password_confirmation" 
                            name="new_password_confirmation" 
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-danger">
                        Đổi mật khẩu
                    </button>
                </form>
            </div>

            <div class="pt-4">
                <a href="/dashboard" class="text-sm link-muted">
                    ← Quay lại Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
