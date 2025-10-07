<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu - PHP Demo</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="content-wide">
            <nav class="nav">
                <div>
                    <h2 style="margin: 0;">Đổi mật khẩu</h2>
                </div>
                <div class="nav-links">
                    <a href="/dashboard" class="nav-link">Dashboard</a>
                    <a href="/profile" class="nav-link">Hồ sơ</a>
                </div>
            </nav>

            <div id="alert" class="alert" style="display:none"></div>
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
                        <input type="password" id="current_password" name="current_password" placeholder="••••••••" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới</label>
                        <input type="password" id="new_password" name="new_password" placeholder="••••••••" required>
                        <div class="form-hint">Tối thiểu 8 ký tự</div>
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Đổi mật khẩu</button>
                </form>
            </div>

            <div class="pt-4">
                <a href="/profile" class="text-sm link-muted">← Quay lại Hồ sơ</a>
            </div>
        </div>
    </div>
    <script>
    (function(){
        const form = document.querySelector('form[action="/profile/password"][method="POST"]');
        const alertBox = document.getElementById('alert');
        function showAlert(message, type) {
            alertBox.textContent = message; alertBox.style.display='block'; alertBox.style.padding='12px 16px'; alertBox.style.borderRadius='8px'; alertBox.style.marginBottom='16px'; alertBox.style.border='1px solid';
            if (type==='error'){alertBox.style.background='#2f1d1d';alertBox.style.borderColor='#7f1d1d';alertBox.style.color='#fecaca';} else {alertBox.style.background='#102a1b';alertBox.style.borderColor='#065f46';alertBox.style.color='#bbf7d0';}
        }
        function buildHeaders(){ const t=document.querySelector('meta[name="csrf-token"]'); const h={'Content-Type':'application/json','Accept':'application/json'}; if(t) h['X-CSRF-TOKEN']=t.getAttribute('content'); const jwt=localStorage.getItem('jwt'); if(jwt) h['Authorization']='Bearer '+jwt; return h; }
        function buildErrorMessage(data,fallback){ if(data&&typeof data==='object'){ if(data.errors){ const k=Object.keys(data.errors)[0]; if(k&&Array.isArray(data.errors[k])) return String(data.errors[k][0]); } if(data.message) return String(data.message);} return fallback||'Có lỗi xảy ra'; }
        if(form){ form.addEventListener('submit', async function(e){ e.preventDefault(); const payload=Object.fromEntries(new FormData(form).entries()); try{ const res=await fetch('/profile/password',{method:'PUT',headers:buildHeaders(),body:JSON.stringify(payload)}); const data=await res.json().catch(()=>({})); if(!res.ok){ showAlert(buildErrorMessage(data,'Đổi mật khẩu thất bại'),'error'); return;} // success: logout and redirect to login with notice
            localStorage.removeItem('jwt');
            window.location.href = '/login?password_changed=1';
        }catch(err){ showAlert('Có lỗi xảy ra','error'); } }); }
    })();
    </script>
</body>
</html>

