<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir Minimarket</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 100%; max-width: 400px; }
        .login-card h2 { text-align: center; color: #1e293b; margin-bottom: 8px; font-size: 24px; }
        .login-card p { text-align: center; color: #64748b; font-size: 14px; margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px; }
        .form-group input { width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.2s; }
        .form-group input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .btn-login { width: 100%; padding: 11px; background: #2563eb; color: white; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s; margin-top: 8px; }
        .btn-login:hover { background: #1d4ed8; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; text-align: center; }
        .demo-accounts { margin-top: 20px; padding-top: 15px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b; }
        .demo-accounts b { color: #334155; }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Kasir Minimarket</h2>
        <p>Silakan masuk untuk memulai shift</p>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@kasir.com">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-login">Masuk Sistem</button>
        </form>

        <div class="demo-accounts">
            <b>Akun Demo:</b><br>
            Admin: admin@kasir.com / password<br>
            Kasir: kasir@kasir.com / password
        </div>
    </div>

</body>
</html>