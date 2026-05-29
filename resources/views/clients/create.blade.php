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
            max-width: 760px;
            margin: 0 auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 25px;
        }

        .avatar {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
            letter-spacing: .5px;
        }

        .header-text {
            display: flex;
            flex-direction: column;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        .page-subtitle {
            font-size: 14px;
            color: #6b7280;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
            margin-bottom: 6px;
            color: #374151;
        }

        input {
            padding: 10px 12px;
            border: 1px solid #dcdfe6;
            border-radius: 6px;
            font-size: 14px;
            transition: .2s;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, .1);
        }

        .error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .form-actions {
            grid-column: span 2;
            margin-top: 10px;
            display: flex;
            justify-content: flex-end;
        }

        .submit-btn {
            background: #2563eb;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: .2s;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        @media(max-width:640px) {

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .form-actions {
                grid-column: span 1;
            }

        }
    </style>



    <div class="form-card">

        <div class="page-header">

            <div class="avatar" id="avatarInitials">
                CL
            </div>

            <div class="header-text">
                <div class="page-title">New Client</div>
                <div class="page-subtitle">Add someone you'll be working with</div>
            </div>

        </div>


        <form method="POST" action="{{ route('clients.store') }}">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" id="firstName" value="{{ old('first_name') }}" required>

                    @error('first_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name') }}">
                </div>


                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" id="lastName" value="{{ old('last_name') }}" required>

                    @error('last_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-group full">
                    <label>Address</label>
                    <input type="text" name="address" value="{{ old('address') }}">
                </div>


                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}">

                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}">

                    @error('phone_number')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-actions">
                    <button class="submit-btn">Save Client</button>
                </div>

            </div>

        </form>

    </div>


    <script>
        const firstName = document.getElementById('firstName');
        const lastName = document.getElementById('lastName');
        const avatar = document.getElementById('avatarInitials');

        function updateAvatar() {

            let f = firstName.value.trim().charAt(0) || '';
            let l = lastName.value.trim().charAt(0) || '';

            let initials = (f + l).toUpperCase();

            avatar.textContent = initials || 'CL';

        }

        firstName.addEventListener('input', updateAvatar);
        lastName.addEventListener('input', updateAvatar);
    </script>

@endsection
