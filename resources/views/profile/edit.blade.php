@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a>
@endsection

@section('content')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .card-box {
            max-width: 720px;
            margin: 20px auto;
            background: #fff;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin: 0 0 22px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 18px;
        }

        .field {
            margin-bottom: 16px;
        }

        label {
            font-weight: 600;
            font-size: 13px;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            height: 44px;
            padding: 0 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        hr.divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 26px 0;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            padding-right: 44px;
        }

        .eye-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6b7280;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .eye-btn:hover {
            color: #374151;
        }

        .btn-save {
            background: #2563eb;
            color: #fff;
            border-radius: 8px;
            padding: 11px 24px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .btn-save:hover {
            background: #1e40af;
        }
    </style>

    <div class="card-box">

        <p class="subtitle">Update your personal details</p>

        {{-- Success --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            {{-- Name --}}
            <div class="field">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
            </div>

            {{-- Email --}}
            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
            </div>

            <hr class="divider">

            {{-- Current Password --}}
            <div class="field">
                <label>Current Password</label>

                <div class="input-wrapper">
                    <input type="password" id="current_password" name="current_password">

                    <button type="button" class="eye-btn" onclick="togglePassword('current_password', this)">
                        <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg class="icon-eye-slash" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- New Password --}}
            <div class="field">
                <label>New Password</label>

                <div class="input-wrapper">
                    <input type="password" id="new_password" name="password">

                    <button type="button" class="eye-btn" onclick="togglePassword('new_password', this)">
                        <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg class="icon-eye-slash" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Confirm --}}
            <div class="field" style="margin-bottom:26px;">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation">
            </div>

            <button type="submit" class="btn-save">
                Save Changes
            </button>

        </form>

    </div>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const eyeIcon = btn.querySelector('.icon-eye');
            const eyeSlashIcon = btn.querySelector('.icon-eye-slash');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.style.display = 'none';
                eyeSlashIcon.style.display = 'block';
            } else {
                input.type = 'password';
                eyeIcon.style.display = 'block';
                eyeSlashIcon.style.display = 'none';
            }
        }
    </script>

@endsection
