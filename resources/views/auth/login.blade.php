<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bean & Brew Pos</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #FDFBF7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: #2C1A11;
        }
        .login-container {
            position: relative;
            width: 950px;
            max-width: 95%;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(74, 46, 27, 0.08);
            display: flex;
            overflow: hidden;
            padding: 40px;
            border: 1px solid #F4EAE1;
        }
        .form-section {
            flex: 1;
            z-index: 2;
        }
        .illustration-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            padding-left: 20px;
        }
        .brand-title {
            font-weight: 800;
            font-size: 1.75rem;
            color: #4A2E1B;
            margin-bottom: 2px;
        }
        .brand-subtitle {
            font-size: 0.9rem;
            color: #6B8E78;
            margin-bottom: 30px;
        }
        .form-control {
            background-color: #FDFBF7;
            border: 1px solid #E6D2C1;
            padding: 12px 15px;
            font-size: 0.95rem;
            border-radius: 10px;
            color: #2C1A11;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #6B8E78;
            box-shadow: 0 0 0 0.25rem rgba(107, 142, 120, 0.15);
        }
        .input-group-text {
            background-color: #FDFBF7;
            border: 1px solid #E6D2C1;
            border-radius: 10px 0 0 10px;
            color: #6B8E78;
        }
        .btn-coffee-custom {
            background-color: #4A2E1B;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            color: #fff;
            transition: all 0.3s;
        }
        .btn-coffee-custom:hover {
            background-color: #2C1A11;
            color: #fff;
        }
        @media (max-width: 768px) {
            .illustration-section {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Bagian Kiri: Form Login -->
        <div class="form-section">
            <div class="mb-4">
                <h2 class="brand-title">Bean & Brew Pos</h2>
                <p class="brand-subtitle">Coffee Shop Management System</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger py-2 small rounded-3 mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <h4 class="fw-bold mb-3">Login</h4>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control border-start-0" placeholder="kasir@gmail.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Keep me logged in</label>
                    </div>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-coffee-custom shadow-sm">
                        Log in
                    </button>
                </div>
            </form>

            <div class="border-top pt-3 mt-3 text-center">
                <small class="text-muted d-block fw-semibold mb-1">Akun Demo:</small>
                <small class="text-secondary d-block">Email: kasir@gmail.com / Password: password</small>
            </div>
        </div>

        <!-- Bagian Kanan: Ilustrasi Coffee Shop -->
        <div class="illustration-section">
            <div style="position: absolute; width: 260px; height: 260px; background: rgba(107, 142, 120, 0.1); border-radius: 50%; z-index: 0; right: 20px; top: 30px;"></div>
            
            <div class="text-center z-1 position-relative">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center text-white rounded-circle shadow-lg" style="width: 110px; height: 110px; background-color: #6B8E78;">
                        <i class="fas fa-mug-hot fa-3x"></i>
                    </div>
                </div>
                <h5 class="fw-bold" style="color: #4A2E1B;">Kelola Kafe Jadi Lebih Mudah</h5>
                <p class="text-muted small px-4">Catat pesanan Coffee, Drink, Food, hingga Desserts secara cepat dan terintegrasi dengan database.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>