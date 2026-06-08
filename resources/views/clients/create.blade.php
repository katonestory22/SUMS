@extends('layouts.app')

@section('title', 'Add Client')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('clients.index') }}">Clients</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .form-card {
            max-width: 850px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .card-header {
            padding: 28px 35px;
            border-bottom: 1px solid #e5e7eb;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .avatar {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #2c5282;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
            letter-spacing: .5px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }

        .page-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }

        .form-body {
            padding: 35px;
        }

        .section-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #2c5282;
            margin-bottom: 18px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .required {
            color: #dc2626;
        }

        .optional {
            color: #9ca3af;
            font-size: 12px;
            font-weight: 500;
        }

        input {
            padding: 11px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all .2s ease;
        }

        input:focus {
            outline: none;
            border-color: #2c5282;
            box-shadow: 0 0 0 3px rgba(44, 82, 130, .12);
        }

        .file-hint {
            margin-top: 5px;
            font-size: 12px;
            color: #6b7280;
        }

        .error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 10px;
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            color: #374151;
            text-decoration: none;
            font-weight: 600;
            transition: .2s;
        }

        .btn-cancel:hover {
            background: #f9fafb;
        }

        .submit-btn {
            background: #2c5282;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .submit-btn:hover {
            background: #23446d;
        }

        @media (max-width: 640px) {

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: span 1;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-cancel,
            .submit-btn {
                width: 100%;
            }
        }
    </style>

    <div class="form-card">

        <div class="card-header">

            <div class="page-header">

                <div class="avatar" id="avatarInitials">
                    CL
                </div>

                <div>
                    <div class="page-title">New Client</div>
                    <div class="page-subtitle">
                        Add someone you'll be working with
                    </div>
                </div>

            </div>

        </div>

        <div class="form-body">

            <form method="POST" action="{{ route('clients.store') }}">
                @csrf

                <div class="section-label">
                    Client Information
                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label>
                            First Name
                            <span class="required">*</span>
                        </label>

                        <input type="text" name="first_name" id="firstName" value="{{ old('first_name') }}" required>

                        @error('first_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>
                            Middle Name
                            <span class="optional">(Optional)</span>
                        </label>

                        <input type="text" name="middle_name" value="{{ old('middle_name') }}">
                    </div>

                    <div class="form-group">
                        <label>
                            Last Name
                            <span class="required">*</span>
                        </label>

                        <input type="text" name="last_name" id="lastName" value="{{ old('last_name') }}" required>

                        @error('last_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label>
                            Address
                            <span class="optional">(Optional)</span>
                        </label>

                        <input type="text" name="address" value="{{ old('address') }}">
                    </div>

                </div>

                <div class="section-label">
                    Contact Information
                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label>
                            Email Address
                            <span class="optional">(Optional)</span>
                        </label>

                        <input type="email" name="email" value="{{ old('email') }}">

                        <span class="file-hint">
                            Used for communication and notifications.
                        </span>

                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>
                            Phone Number
                            <span class="optional">(Optional)</span>
                        </label>

                        <input type="text" name="phone_number" value="{{ old('phone_number') }}">

                        <span class="file-hint">
                            Include country code if applicable.
                        </span>

                        @error('phone_number')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="form-actions">

                    <a href="{{ route('clients.index') }}" class="btn-cancel">
                        Cancel
                    </a>

                    <button type="submit" class="submit-btn">
                        Save Client
                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>
        const firstName = document.getElementById('firstName');
        const lastName = document.getElementById('lastName');
        const avatar = document.getElementById('avatarInitials');

        function updateAvatar() {
            let f = firstName.value.trim().charAt(0) || '';
            let l = lastName.value.trim().charAt(0) || '';

            avatar.textContent = (f + l).toUpperCase() || 'CL';
        }

        firstName.addEventListener('input', updateAvatar);
        lastName.addEventListener('input', updateAvatar);
    </script>

@endsection
