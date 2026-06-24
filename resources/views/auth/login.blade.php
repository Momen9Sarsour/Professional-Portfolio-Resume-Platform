<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f2f8;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 28px;
            padding: 48px 40px;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border: 1px solid #e8edf5;
        }

        .login-card .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-card .logo h2 {
            font-weight: 800;
            color: #1a2035;
        }

        .login-card .logo span {
            color: #2f7bff;
        }

        .login-card .logo p {
            color: #7a869a;
            font-size: 14px;
            margin-top: 4px;
        }

        .form-control {
            border: 1.5px solid #e8edf5;
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #2f7bff;
            box-shadow: 0 0 0 3px rgba(47,123,255,0.1);
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #1a2035;
        }

        .btn-login {
            background: #2f7bff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 15px;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: #1a5fcc;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(47,123,255,0.3);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .form-check-label {
            font-size: 13px;
            color: #475569;
        }

        .register-link {
            color: #2f7bff;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .input-group-text {
            background: transparent;
            border: 1.5px solid #e8edf5;
            border-right: none;
            border-radius: 14px 0 0 14px;
            color: #94a3b8;
        }

        .input-group .form-control {
            border-radius: 0 14px 14px 0;
            border-left: none;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
            color: #94a3b8;
            font-size: 12px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e8edf5;
        }

        .back-home {
            color: #7a869a;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }

        .back-home:hover {
            color: #2f7bff;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">
            <a href="{{ route('home') }}" style="text-decoration: none;">
                <h2><i class="bi bi-code-square" style="color:#2f7bff;"></i> <span>Portfolio</span></h2>
            </a>
            <p>Welcome back! Login to your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                @error('password')
                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="register-link" style="font-size: 13px;">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
            </button>
        </form>

        <div class="divider">New here?</div>

        <p class="text-center" style="font-size: 14px; color: #475569;">
            Don't have an account?
            <a href="{{ route('register') }}" class="register-link">Create one now</a>
        </p>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="back-home">
                <i class="bi bi-arrow-left me-1"></i> Back to Home
            </a>
        </div>
    </div>
</body>
</html>
