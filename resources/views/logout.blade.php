<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng xuất</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="content text-center">
            <h1>Đang đăng xuất...</h1>
            <p>Bạn sẽ được chuyển về trang chủ.</p>
        </div>
    </div>
    <script>
    (function(){
        localStorage.removeItem('jwt');
        window.location.replace('/');
    })();
    </script>
</body>
</html>

