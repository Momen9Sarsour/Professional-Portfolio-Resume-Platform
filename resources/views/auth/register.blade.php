<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Portfolio</title>
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

        .register-card {
            background: white;
            border-radius: 28px;
            padding: 40px 36px;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border: 1px solid #e8edf5;
        }

        .register-card .logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .register-card .logo h2 {
            font-weight: 800;
            color: #1a2035;
        }

        .register-card .logo span {
            color: #2f7bff;
        }

        .register-card .logo p {
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

        .btn-register {
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

        .btn-register:hover {
            background: #1a5fcc;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(47,123,255,0.3);
        }

        .btn-register:active {
            transform: scale(0.98);
        }

        .login-link {
            color: #2f7bff;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link:hover {
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

        .help-text {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="logo">
            <a href="{{ route('home') }}" style="text-decoration: none;">
                <h2><i class="bi bi-code-square" style="color:#2f7bff;"></i> <span>Portfolio</span></h2>
            </a>
            <p>Create your account</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="name" class="form-control" placeholder="Your full name" value="{{ old('name') }}" required autofocus>
                </div>
                @error('name')
                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="username" value="{{ old('username') }}">
                </div>
                <div class="help-text">Optional. Used for your public CV URL</div>
                @error('username')
                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="help-text">Minimum 8 characters</div>
                @error('password')
                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-register">
                <i class="bi bi-person-plus me-2"></i> Create Account
            </button>
        </form>

        <div class="divider">Already have an account?</div>

        <p class="text-center" style="font-size: 14px; color: #475569;">
            <a href="{{ route('login') }}" class="login-link">Sign in to your account</a>
        </p>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="back-home">
                <i class="bi bi-arrow-left me-1"></i> Back to Home
            </a>
        </div>
    </div>
</body>
</html>
