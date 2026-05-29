@extends('layouts.app')

@section('title', 'Create User')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Home</a>
    <a href="{{ route('users.index') }}" class="back-btn">
        Back
    </a>
@endsection

@section('content')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .form-wrapper {
            max-width: 760px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-card {
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .header {
            margin-bottom: 18px;
        }

        .title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        input,
        select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            background: #fff;
            transition: 0.2s;
        }

        input:focus,
        select:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .hint {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .btn-submit {
            width: 100%;
            margin-top: 10px;
            background: #2563eb;
            color: white;
            padding: 11px;
            border-radius: 10px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 12px;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 12px;
            border: 1px solid #fecaca;
        }

        .error-list {
            margin: 0;
            padding-left: 18px;
        }

        .file-input {
            padding: 8px;
        }
    </style>

    <div class="form-wrapper">

        <div class="form-card">

            <div class="header">
                <div class="title">Create system user</div>
                <div class="subtitle">Add a user with role-based access and identity details</div>
            </div>

            {{-- FEEDBACK --}}
            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>

                <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name">
                </div>

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                    <div class="hint">Password will be generated from last name (uppercase)</div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address">
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth">
                </div>

                <div class="form-group">
                    <label>National ID</label>
                    <input type="text" name="national_id">
                </div>

                <div class="form-group">
                    <label>Passport Photo</label>
                    <input class="file-input" type="file" name="passport_photo" accept="image/*">
                    <div class="hint">Upload clear passport-size image (JPG/PNG)</div>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="director">Director</option>
                        <option value="finance">Finance</option>
                        <option value="technical">Technical</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    Create User
                </button>

            </form>

        </div>

    </div>

@endsection
