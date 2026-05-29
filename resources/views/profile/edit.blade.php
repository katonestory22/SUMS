@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a>
@endsection

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .card-box {
            max-width: 720px;
            margin: auto;
            background: #fff;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
        }

        .form-control {
            height: 44px;
            border-radius: 8px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #2563eb;
        }

        .input-wrapper {
            position: relative;
        }

        .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6b7280;
        }

        .btn-save {
            background: #2563eb;
            color: #fff;
            border-radius: 8px;
            padding: 10px 22px;
            font-weight: 600;
            border: none;
        }

        .btn-save:hover {
            background: #1e40af;
        }
    </style>

    <div class="card-box">

        <p class="text-muted mb-4">Update your personal details</p>

        {{-- Success --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
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
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}"
                    required>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}"
                    required>
            </div>

            <hr class="my-4">

            {{-- Current Password --}}
            <div class="mb-3">
                <label class="form-label">Current Password</label>

                <div class="input-wrapper">
                    <input type="password" id="current_password" name="current_password" class="form-control">

                    <button type="button" class="eye-btn" onclick="togglePassword('current_password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            {{-- New Password --}}
            <div class="mb-3">
                <label class="form-label">New Password</label>

                <div class="input-wrapper">
                    <input type="password" id="new_password" name="password" class="form-control">

                    <button type="button" class="eye-btn" onclick="togglePassword('new_password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Confirm --}}
            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">

            </div>

            <button type="submit" class="btn-save">
                Save Changes
            </button>

        </form>

    </div>

    <script>
        function togglePassword(id, btn) {

            const input = document.getElementById(id);
            const icon = btn.querySelector('i');

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

@endsection
