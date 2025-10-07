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

            <div id="alert" class="alert" style="display:none"></div>
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
            <div class="pt-4">
                <a href="/profile/password" class="btn btn-secondary">Đổi mật khẩu</a>
                <a href="/dashboard" class="text-sm link-muted" style="margin-left:12px">← Quay lại Dashboard</a>
            </div>
        </div>
    </div>
    <script>
    (function(){
        const form = document.querySelector('form[action="/profile/update"][method="POST"]');
        const alertBox = document.getElementById('alert');
        function showAlert(message, type) {
            alertBox.textContent = message; alertBox.style.display='block'; alertBox.style.padding='12px 16px'; alertBox.style.borderRadius='8px'; alertBox.style.marginBottom='16px'; alertBox.style.border='1px solid';
            if (type==='error'){alertBox.style.background='#2f1d1d';alertBox.style.borderColor='#7f1d1d';alertBox.style.color='#fecaca';} else {alertBox.style.background='#102a1b';alertBox.style.borderColor='#065f46';alertBox.style.color='#bbf7d0';}
        }
        function buildHeaders(){ const t=document.querySelector('meta[name="csrf-token"]'); const h={'Content-Type':'application/json','Accept':'application/json'}; if(t) h['X-CSRF-TOKEN']=t.getAttribute('content'); const jwt=localStorage.getItem('jwt'); if(jwt) h['Authorization']='Bearer '+jwt; return h; }
        function buildErrorMessage(data,fallback){ if(data&&typeof data==='object'){ if(data.errors){ const k=Object.keys(data.errors)[0]; if(k&&Array.isArray(data.errors[k])) return String(data.errors[k][0]); } if(data.message) return String(data.message);} return fallback||'Có lỗi xảy ra'; }
        if(form){ form.addEventListener('submit', async function(e){ e.preventDefault(); const payload=Object.fromEntries(new FormData(form).entries()); try{ const res=await fetch('/profile/update',{method:'PUT',headers:buildHeaders(),body:JSON.stringify(payload)}); const data=await res.json().catch(()=>({})); if(!res.ok){ showAlert(buildErrorMessage(data,'Cập nhật thất bại'),'error'); return;} showAlert('Cập nhật thành công','success'); }catch(err){ showAlert('Có lỗi xảy ra','error'); } }); }
    })();
    </script>
</body>
</html>
