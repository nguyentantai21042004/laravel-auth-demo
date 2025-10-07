<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body{font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;line-height:1.5;margin:0;background:#0f172a;color:#e2e8f0}
        .container{max-width:960px;margin:0 auto;padding:48px 24px}
        .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:32px}
        a{color:#60a5fa;text-decoration:none}
        a:hover{text-decoration:underline}
        .nav{display:flex;gap:16px;margin-top:16px}
        .title{font-size:28px;margin:0 0 8px}
        .subtitle{opacity:.8;margin:0 0 24px}
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1 class="title">About this app</h1>
            <p class="subtitle">A simple page to demonstrate routing between views.</p>
            <div class="nav">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('about') }}">About</a>
            </div>
        </div>
    </div>
</body>
</html>

