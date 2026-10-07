<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Sistem Inventaris Laboratorium">
    <title>Login — Sistem Inventaris Laboratorium</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #06b6d4;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated background blobs */
        body::before, body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: float 8s ease-in-out infinite;
        }
        body::before {
            width: 500px; height: 500px;
            background: var(--primary);
            top: -100px; left: -100px;
        }
        body::after {
            width: 400px; height: 400px;
            background: var(--secondary);
            bottom: -100px; right: -100px;
            animation-delay: -4s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -20px) scale(1.05); }
        }

        .login-card {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
        }

        .login-logo {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            color: #fff;
            margin: 0 auto 1.25rem;
            box-shadow: 0 8px 24px rgba(79,70,229,0.4);
        }

        .login-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #f1f5f9;
            text-align: center;
        }

        .login-sub {
            font-size: 0.82rem;
            color: #64748b;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 500;
            color: #94a3b8;
            margin-bottom: 0.35rem;
        }

        .form-control {
            background: rgba(15,23,42,0.6);
            border: 1px solid #334155;
            color: #e2e8f0;
            border-radius: 10px;
            padding: 0.65rem 0.9rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            background: rgba(15,23,42,0.8);
            border-color: var(--primary);
            color: #e2e8f0;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.2);
        }

        .form-control::placeholder { color: #475569; }

        .input-icon-wrap {
            position: relative;
        }

        .input-icon-wrap .icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #475569;
            font-size: 1rem;
            pointer-events: none;
        }

        .input-icon-wrap .form-control {
            padding-left: 2.5rem;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
            border: none;
            color: #fff;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            transition: all 0.25s;
            letter-spacing: 0.02em;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(79,70,229,0.4);
            color: #fff;
        }

        .btn-login:active { transform: translateY(0); }

        .demo-accounts {
            background: rgba(79,70,229,0.08);
            border: 1px solid rgba(79,70,229,0.2);
            border-radius: 12px;
            padding: 0.85rem;
            margin-top: 1.5rem;
        }

        .demo-title {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .demo-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.78rem;
            padding: 0.2rem 0;
        }

        .demo-item .label { color: #94a3b8; }
        .demo-item .value { color: #818cf8; font-family: monospace; }

        .alert-login {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            color: #f87171;
            border-radius: 10px;
            font-size: 0.85rem;
            padding: 0.6rem 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <i class="bi bi-flask"></i>
        </div>
        <h1 class="login-title">Selamat Datang</h1>
        <p class="login-sub">Sistem Inventaris Laboratorium<br>Masuk untuk melanjutkan</p>

        @if($errors->any())
            <div class="alert-login mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-3 p-2 rounded" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: #34d399; font-size: 0.85rem;">
                <i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-envelope icon"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="nama@email.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-lock icon"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" style="background-color: #1e293b; border-color: #475569;">
                <label class="form-check-label" for="remember" style="font-size: 0.83rem; color: #64748b;">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-arrow-right-circle me-2"></i>Masuk ke Sistem
            </button>
        </form>

        <div class="demo-accounts">
            <div class="demo-title">Akun Demo</div>
            <div class="demo-item">
                <span class="label">Kepala Lab:</span>
                <span class="value">kepala@lab.com / password</span>
            </div>
            <div class="demo-item">
                <span class="label">Laboran:</span>
                <span class="value">laboran@lab.com / password</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
