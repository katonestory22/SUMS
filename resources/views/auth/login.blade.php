<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            text-align: center;
        }

        .logo {
            max-width: 90px;
            margin-bottom: 25px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            text-align: left;
            display: block;
        }

        .form-control {
            height: 46px;
            border-radius: 8px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #2563eb;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6b7280;
        }

        .btn-login {
            background-color: #2c5282;
            border: none;
            border-radius: 8px;
            padding: 10px 30px;
            font-weight: 600;
            font-size: 14px;
            width: auto;
        }

        .btn-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="login-card">

        {{-- LOGO ONLY --}}
        <img src="{{ asset('images/swahililogo.png') }}" class="logo" alt="Swahili Units Logo">

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger text-start">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3 text-start">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            {{-- Password --}}
            <div class="mb-3 text-start">
                <label class="form-label">Password</label>

                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-control" required>

                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Remember --}}
            <div class="form-check mb-3 text-start">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>

            {{-- Login Button CENTERED --}}
            <div class="btn-wrapper">
                <button type="submit" class="btn btn-primary btn-login">
                    Login
                </button>
            </div>

        </form>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = event.currentTarget.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>

</body>

</html>
