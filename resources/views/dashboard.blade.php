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
                    <button id="logoutBtn" class="nav-link" style="background: none; border: none; cursor: pointer;">Đăng xuất</button>
                </div>
            </nav>

            <div class="space-y-2 mb-6">
                <h1>Dashboard</h1>
                <p>Thông tin xác thực bằng JWT và danh sách người dùng.</p>
            </div>

            <div class="stats-grid" id="stats">
                <div class="stat-card">
                    <div class="stat-label">JWT Token</div>
                    <div class="stat-value" id="stat-jwt">Không có</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">JWT Exp</div>
                    <div class="stat-value" id="stat-exp">-</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Email</div>
                    <div class="stat-value" id="stat-email">-</div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin tài khoản (từ JWT)</h3>
                        <p class="card-description">Hiển thị token và payload đã decode</p>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="link-muted">Token:</span>
                            <span id="jwt-token" style="max-width:70%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:inline-block;vertical-align:bottom">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="link-muted">sub (user id):</span>
                            <span id="jwt-sub">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="link-muted">email:</span>
                            <span id="jwt-email">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="link-muted">exp:</span>
                            <span id="jwt-exp">-</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách người dùng (API)</h3>
                        <p class="card-description">Tự động cập nhật mỗi 5 giây từ /api/users</p>
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
        const statJwt = document.getElementById('stat-jwt');
        const statExp = document.getElementById('stat-exp');
        const statEmail = document.getElementById('stat-email');
        const jwtTokenEl = document.getElementById('jwt-token');
        const jwtSubEl = document.getElementById('jwt-sub');
        const jwtEmailEl = document.getElementById('jwt-email');
        const jwtExpEl = document.getElementById('jwt-exp');

        function decodeJwt(token) {
            try {
                const parts = token.split('.');
                if (parts.length !== 3) return null;
                const payload = JSON.parse(atob(parts[1].replace(/-/g, '+').replace(/_/g, '/')));
                return payload;
            } catch (_) { return null; }
        }

        function fmtTs(ts) {
            if (!ts) return '-';
            const d = new Date(ts * 1000);
            return d.toLocaleString();
        }

        function updateJwtUI() {
            const token = localStorage.getItem('jwt');
            if (!token) {
                statJwt.textContent = 'Không có';
                statExp.textContent = '-';
                statEmail.textContent = '-';
                jwtTokenEl.textContent = '-';
                jwtSubEl.textContent = '-';
                jwtEmailEl.textContent = '-';
                jwtExpEl.textContent = '-';
                return null;
            }
            statJwt.textContent = 'Có';
            jwtTokenEl.textContent = token;
            const payload = decodeJwt(token);
            if (payload) {
                statEmail.textContent = payload.email || '-';
                statExp.textContent = fmtTs(payload.exp);
                jwtSubEl.textContent = payload.sub || '-';
                jwtEmailEl.textContent = payload.email || '-';
                jwtExpEl.textContent = fmtTs(payload.exp);
            }
            return token;
        }

        function renderUsers(list) {
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
        }

        async function fetchUsers() {
            const token = localStorage.getItem('jwt');
            if (!token) {
                usersEl.innerHTML = '<div class="text-sm link-muted">Chưa đăng nhập</div>';
                return;
            }
            try {
                const res = await fetch('/api/users', { headers: { 'Authorization': 'Bearer ' + token } });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                const list = Array.isArray(data?.data) ? data.data : (Array.isArray(data) ? data : []);
                renderUsers(list);
            } catch (e) {
                usersEl.innerHTML = '<div class="text-sm" style="color:#ef4444">Lỗi tải người dùng: '+ (e.message || 'unknown') +'</div>';
            }
        }

        // init
        updateJwtUI();
        fetchUsers();
        // realtime via polling 5s
        setInterval(() => { updateJwtUI(); fetchUsers(); }, 5000);

        // logout clears token and back to login
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                localStorage.removeItem('jwt');
                window.location.href = '/login';
            });
        }
    })();
    </script>
</body>
</html>
